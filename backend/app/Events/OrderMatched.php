<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderMatched implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $buyOrder;
    public $sellOrder;
    public $tradeValue;
    public $commission;

    public function __construct(Order $buyOrder, Order $sellOrder, $tradeValue, $commission)
    {
        $this->buyOrder = $buyOrder;
        $this->sellOrder = $sellOrder;
        $this->tradeValue = $tradeValue;
        $this->commission = $commission;
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('user.' . $this->buyOrder->user_id),
            new PrivateChannel('user.' . $this->sellOrder->user_id),
        ];
    }

    public function broadcastWith()
    {
        return [
            'buy_order' => $this->buyOrder->toArray(),
            'sell_order' => $this->sellOrder->toArray(),
            'trade_value' => $this->tradeValue,
            'commission' => $this->commission,
        ];
    }
}
