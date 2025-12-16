<?php
namespace App\Services\V1;

use App\Models\Order;
use App\Models\User;

class WalletService
{
    public function executeTrade(
        Order $buyOrder,
        Order $sellOrder,
        string $amount,
        string $total,
        string $commission
    ): void {
        $buyer  = $buyOrder->user()->lockForUpdate()->first();
        $seller = $sellOrder->user()->lockForUpdate()->first();

        $buyerAsset = $buyer->assets()->firstOrCreate(
            ['symbol' => $buyOrder->symbol],
            ['amount' => 0, 'locked_amount' => 0]
        );

        $sellerAsset = $seller->assets()
            ->where('symbol', $sellOrder->symbol)
            ->lockForUpdate()
            ->first();

        $buyer->balance = bcsub(
            $buyer->balance,
            $commission,
            config('constant.decimal.price')
        );

        $buyerAsset->amount = bcadd(
            $buyerAsset->amount,
            $amount,
            config('constant.decimal.amount')
        );

        $sellerAsset->locked_amount = bcsub(
            $sellerAsset->locked_amount,
            $amount,
            config('constant.decimal.amount')
        );

        $seller->balance = bcadd(
            $seller->balance,
            $total,
            config('constant.decimal.price')
        );

        $buyer->save();
        $seller->save();
        $buyerAsset->save();
        $sellerAsset->save();
    }

    public function unlockUsd(User $user, string $amount): void
    {
        $user->balance = bcadd(
            $user->balance,
            $amount,
            config('constant.decimal.price')
        );
        $user->save();
    }
}
