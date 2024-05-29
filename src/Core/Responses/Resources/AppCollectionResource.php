<?php

namespace Core\Responses\Resources;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AppCollectionResource extends ResourceCollection
{
    protected function formatCollection($data): array
    {
        $response = [
            'items' => $data->collection,
        ];

        if ($data->resource instanceof LengthAwarePaginator) {
            $response['pagination'] = [
                'total' => $data->total(),
                'count' => $data->count(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'total_pages' => $data->lastPage(),
            ];
        }

        return $response;
    }
}
