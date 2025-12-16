<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ProfileResource;
use App\Http\Traits\ApiResponseTrait;


class ProfileController extends Controller
{
    use ApiResponseTrait;

    public function show()
    {
        $user = auth()->user()->load('assets');

        return $this->success(new ProfileResource($user), 'Profile fetched successfully');
    }
}
