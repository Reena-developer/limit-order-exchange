<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreOrderRequest;
use App\Http\Resources\Api\V1\OrderResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Order;
use App\Models\User;
use App\Services\V1\OrderMatchingService;
use App\Services\V1\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use ApiResponseTrait;

    protected WalletService $walletService;
    protected OrderMatchingService $matchingService;

    public function __construct(
        WalletService $walletService,
        OrderMatchingService $matchingService
    ) {
        $this->walletService = $walletService;
        $this->matchingService = $matchingService;
    }

    public function store(StoreOrderRequest $request)
    {
        try {
            $order = $request->side === 'buy'
                ? $this->createBuyOrder($request)
                : $this->createSellOrder($request);

            $this->matchingService->match($order);

            return $this->success(new OrderResource($order), 'Order placed successfully', 201);

        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), null, 422);
        }
    }

    private function createBuyOrder(StoreOrderRequest $request): Order
    {
        return DB::transaction(function () use ($request) {

            $authUser = $request->user();

            $user = User::where('id', $authUser->id)
            ->lockForUpdate()
            ->first();
            
            
            $orderValue = bcmul($request->amount, $request->price, config('constant.decimal.price'));
            $commission = bcmul($orderValue, config('constant.commission_rate'), config('constant.decimal.price'));
            $totalReserve = bcadd($orderValue, $commission, config('constant.decimal.price'));
            
            if (bccomp($user->balance, $totalReserve, config('constant.decimal.price')) < 0) {
                throw new \Exception('Insufficient balance for order + commission');
            }

            $this->walletService->lockUsd($user, $totalReserve);

            return Order::create([
                'user_id'       => $user->id,
                'symbol'        => $request->symbol,
                'side'          => 'buy',
                'price'         => $request->price,
                'amount'        => $request->amount,
                'status'        => OrderStatus::OPEN,
                'locked_amount' => $totalReserve,
            ]);
        });
    }

    private function createSellOrder(StoreOrderRequest $request): Order
    {
        return DB::transaction(function () use ($request) {
            
            $authUser = $request->user();

            $user = User::where('id', $authUser->id)
            ->lockForUpdate()
            ->first();

            $this->walletService->lockAsset(
                $user,
                $request->symbol,
                $request->amount
            );

            return Order::create([
                'user_id'       => $user->id,
                'symbol'        => $request->symbol,
                'side'          => 'sell',
                'price'         => $request->price,
                'amount'        => $request->amount,
                'status'        => OrderStatus::OPEN,
                'locked_amount' => $request->amount,
            ]);
        });
    }

    public function cancel(Order $order)
    {
        try {
            $user = auth()->user();

            if ($order->user_id !== $user->id) {
                return $this->error('Unauthorized', null, 403);
            }

            return DB::transaction(function () use ($order, $user) {

                $order = Order::where('id', $order->id)
                    ->where('status', OrderStatus::OPEN)
                    ->lockForUpdate()
                    ->first();

                if (!$order) {
                    throw new \Exception('Order cannot be cancelled');
                }

                if ($order->side === 'buy') {
                    $this->walletService->unlockUsd($user, $order->locked_amount);
                } else {
                    $this->walletService->unlockAsset(
                        $user,
                        $order->symbol,
                        $order->amount
                    );
                }

                $order->update([
                    'status'        => OrderStatus::CANCELLED,
                    'locked_amount' => 0,
                ]);

                return $order;
            });

        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), null, 422);
        }
    }

    public function index(Request $request)
    {
        $query = $request->user()->orders();
        
        if ($request->filled('symbol')) {
            $query->where('symbol', $request->symbol);
        }

        if ($request->filled('side')) {
            $query->where('side', $request->side);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        
        $sortBy = $request->get('sortBy', 'id'); 
        $sortDir = $request->get('sortDir', 'desc');
        $query->orderBy($sortBy, $sortDir);
        
        $perPage = (int) $request->get('perPage', config('constant.pagination.per_page'));
        $orders = $query->paginate($perPage);
        
        return $this->paginated(OrderResource::collection($orders));
    }

}
