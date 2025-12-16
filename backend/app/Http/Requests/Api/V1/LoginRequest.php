<?php 
namespace App\Http\Requests\Api\V1;

use App\Http\Requests\Api\V1\BaseFormRequest;

class LoginRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
        ];
    }
}

