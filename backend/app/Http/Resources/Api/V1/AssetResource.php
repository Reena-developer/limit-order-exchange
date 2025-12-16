<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class AssetResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'symbol' => $this->symbol,
            'amount' => (float) $this->amount,
            'locked' => (float) $this->locked_amount,
        ];
    }
}
