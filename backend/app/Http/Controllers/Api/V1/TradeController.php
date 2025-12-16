<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Trade;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $trades = Trade::where('buyer_id', $request->user()->id)
            ->orWhere('seller_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        return $this->success($trades);
    }
}
