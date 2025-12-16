<?php
namespace App\Services\V1;

use App\Models\Order;
use App\Models\User;

class WalletService
{
        public function lockUsd(User $user, string $amount): void
    {
        $user->balance = bcsub($user->balance, $amount, config('constant.decimal.price'));
        $user->locked_balance = bcadd($user->locked_balance, $amount, config('constant.decimal.price'));
        $user->save();
    }

    public function unlockUsd(User $user, string $amount): void
    {
        $user->balance = bcadd($user->balance, $amount, config('constant.decimal.price'));
        $user->locked_balance = bcsub($user->locked_balance, $amount, config('constant.decimal.price'));
        $user->save();
    }

    public function debitUsd(User $user, string $amount): void
    {
        $user->locked_balance = bcsub($user->locked_balance, $amount, config('constant.decimal.price'));
        $user->save();
    }

    public function creditUsd(User $user, string $amount): void
    {
        $user->balance = bcadd($user->balance, $amount, config('constant.decimal.price'));
        $user->save();
    }

    public function lockAsset(User $user, string $symbol, string $amount): void
    {
        $asset = $user->assets()->where('symbol', $symbol)->lockForUpdate()->first();
        if (!$asset) {
            throw new \Exception("You do not own {$symbol}");
        }
        $asset->amount = bcsub($asset->amount, $amount, config('constant.decimal.amount'));
        if (bccomp($asset->amount, $amount, config('constant.decimal.amount')) < 0) {
            throw new \Exception("Insufficient {$symbol} balance");
        }
        $asset->locked_amount = bcadd($asset->locked_amount, $amount, config('constant.decimal.amount'));
        $asset->save();
    }

    public function unlockAsset(User $user, string $symbol, string $amount): void
    {
        $asset = $user->assets()->where('symbol', $symbol)->lockForUpdate()->first();
        $asset->amount = bcadd($asset->amount, $amount, config('constant.decimal.amount'));
        $asset->locked_amount = bcsub($asset->locked_amount, $amount, config('constant.decimal.amount'));
        $asset->save();
    }

    public function creditAsset(User $user, string $symbol, string $amount): void
    {
        $asset = $user->assets()->firstOrCreate(
            ['symbol' => $symbol],
            ['amount' => 0, 'locked_amount' => 0]
        );
        $asset->amount = bcadd($asset->amount, $amount, config('constant.decimal.amount'));
        $asset->save();
    }
}
