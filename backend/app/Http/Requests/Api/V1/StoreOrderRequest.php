<?php 
namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\V1\BaseFormRequest;

class StoreOrderRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'symbol' => 'required|string|in:BTC,ETH',
            'side'   => 'required|string|in:buy,sell',
            'price'  => 'required|numeric|min:0.01',
            'amount' => 'required|numeric|min:0.00000001',
        ];  
    }
}
