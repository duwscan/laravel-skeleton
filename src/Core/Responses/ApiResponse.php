<?php

namespace Core\Responses;

use Core\Exceptions\ExceptionCode;

trait ApiResponse
{
    protected array $metaData = [];

    public function returnWithRequestParams(): self
    {
        $this->metaData['params'] = request()->all();

        return $this;
    }

    public function responseSuccess(
        $data,
        $message = null,
        $code = 200,
    ): \Illuminate\Http\JsonResponse {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'meta' => $this->metaData,
        ], $code);
    }

    public function responseError(
        $message = null,
        $description = null,
        ExceptionCode|int $code = 400
    ): \Illuminate\Http\JsonResponse {
        return response()->json([
            'status' => 'error',
            'code' => $code instanceof ExceptionCode ? $code->value : $code,
            'message' => $code instanceof ExceptionCode ? $code->getMessage() : $message,
            'description' => $code instanceof ExceptionCode ? $code->getDescription() : $description,
        ], $code);
    }
}
