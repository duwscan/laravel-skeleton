<?php

namespace Core\Responses\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class AppJsonResource extends JsonResource
{
    public function toArray(Request $request): array|\JsonSerializable|\Illuminate\Contracts\Support\Arrayable
    {
        if (is_null($this->resource)) {
            return [];
        }

        return $this->data($request);
    }

    abstract public function data(Request $request): array;
}
