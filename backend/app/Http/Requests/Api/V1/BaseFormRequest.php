<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Traits\ApiResponseTrait;

class BaseFormRequest extends FormRequest
{
    use ApiResponseTrait;

    /**
     * Handle failed validation
     *
     * This automatically returns a consistent API response
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->error(
                'Validation failed',
                $validator->errors(),
                422
            )
        );
    }

    /**
     * Optional: authorize by default
     */
    public function authorize(): bool
    {
        return true;
    }
}
