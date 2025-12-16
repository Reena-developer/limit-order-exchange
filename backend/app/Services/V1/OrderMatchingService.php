<?php
namespace App\Services\V1;

use App\Models\Order;
use App\Models\Trade;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\DB;
use App\Services\V1\WalletService;

class OrderMatchingService
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function match(Order $newOrder): void
    {
        DB::transaction(function () use ($newOrder) {

            $newOrder = Order::where('id', $newOrder->id)
                ->where('status', OrderStatus::OPEN)
                ->lockForUpdate()
                ->first();

            if (!$newOrder) {
                return;
            }

            $opposite = $newOrder->side === 'buy' ? 'sell' : 'buy';

            $matchOrder = Order::where('symbol', $newOrder->symbol)
                ->where('side', $opposite)
                ->where('status', OrderStatus::OPEN)
                ->when($newOrder->side === 'buy', fn ($q) =>
                    $q->where('price', '<=', $newOrder->price)
                )
                ->when($newOrder->side === 'sell', fn ($q) =>
                    $q->where('price', '>=', $newOrder->price)
                )
                ->orderBy('created_at')
                ->lockForUpdate()
                ->first();

            if (!$matchOrder) {
                return;
            }

            if (bccomp($newOrder->amount, $matchOrder->amount, config('constant.decimal.amount')) !== 0) {
                return;
            }

            $buyOrder  = $newOrder->side === 'buy' ? $newOrder : $matchOrder;
            $sellOrder = $newOrder->side === 'sell' ? $newOrder : $matchOrder;

            $tradeAmount = $buyOrder->amount;
            $tradePrice  = $sellOrder->price;

            $tradeValue = bcmul($tradeAmount, $tradePrice, config('constant.decimal.price'));
            $commission = bcmul($tradeValue, config('constant.commission_rate'), config('constant.decimal.price'));

            $buyer  = $buyOrder->user()->lockForUpdate()->first();
            $seller = $sellOrder->user()->lockForUpdate()->first();

            // Buyer
            $this->walletService->debitUsd($buyer, $commission);
            $this->walletService->creditAsset($buyer, $buyOrder->symbol, $tradeAmount);

            // Seller
            $this->walletService->creditUsd($seller, $tradeValue);
            $this->walletService->unlockAsset($seller, $sellOrder->symbol, $tradeAmount);

            // Release unused USD lock (price improvement case)
            $refund = bcsub($buyOrder->locked_amount, bcadd($tradeValue, $commission, 8), 8);
            if (bccomp($refund, '0', 8) === 1) {
                $this->walletService->creditUsd($buyer, $refund);
            }

            // Orders
            $buyOrder->update(['status' => OrderStatus::FILLED, 'locked_amount' => 0]);
            $sellOrder->update(['status' => OrderStatus::FILLED, 'locked_amount' => 0]);

            Trade::create([
                'buy_order_id'  => $buyOrder->id,
                'sell_order_id' => $sellOrder->id,
                'buyer_id'      => $buyer->id,
                'seller_id'     => $seller->id,
                'symbol'        => $buyOrder->symbol,
                'price'         => $tradePrice,
                'amount'        => $tradeAmount,
                'total'         => $tradeValue,
                'commission'    => $commission,
            ]);

            event(new OrderMatched($buyOrder, $sellOrder, $tradeValue, $commission));
        });
    }
}
