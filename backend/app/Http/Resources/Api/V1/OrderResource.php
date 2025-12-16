<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'symbol'     => $this->symbol,
            'side'       => $this->side,
            'amount'     => $this->amount,
            'price'      => $this->price,
            'total'      => bcmul($this->amount, $this->price, 8),
            'status'     => $this->status,
            'createdAt'  => $this->created_at->toDateTimeString(),
            'updatedAt'  => $this->updated_at->toDateTimeString(),
        ];
    }
}
