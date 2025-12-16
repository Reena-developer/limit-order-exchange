<?php 
namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'usd_balance' => (float) $this->balance,
            'assets' => AssetResource::collection($this->assets ?? collect()),
        ];
    }
}
