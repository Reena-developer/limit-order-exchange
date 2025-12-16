<?php

namespace App\Http\Traits;

use Illuminate\Http\Resources\Json\ResourceCollection;

trait ApiResponseTrait
{
    /**
     * Success response
     */
    public function success($data = null, string $message = '', int $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    /**
     * Error response
     */
    public function error(string $message = '', $errors = null, int $status = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }

    /**
     * Paginated response
     */
    public function paginated(ResourceCollection $collection, string $message = '')
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $collection->collection,
            'pagination' => [
                'total' => $collection->total(),
                'count' => $collection->count(),
                'per_page' => $collection->perPage(),
                'current_page' => $collection->currentPage(),
                'total_pages' => $collection->lastPage(),
            ]
        ]);
    }
}
