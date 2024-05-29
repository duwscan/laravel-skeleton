<?php

namespace Core\Exceptions;

use Core\Responses\ApiResponse;
use Exception;
use Illuminate\Http\Request;

class InternalException extends Exception
{
    use ApiResponse;

    protected string $description;
    protected ExceptionCode $internalCode;

    public static function new(
        ExceptionCode $code,
        ?string $message = null,
        ?string $description = null,
        ?int $statusCode = null,
    ): static {
        $exception = new static(
            $message ?? $code->getMessage(),
            $statusCode ?? $code->getStatusCode(),
        );

        $exception->internalCode = $code;
        $exception->description = $description ?? $code->getDescription();

        return $exception;
    }

    public function getInternalCode(): ExceptionCode
    {
        return $this->internalCode;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function report(): void
    {
        // ...
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request)
    {
        return $this->responseError(
            $this->getMessage(),
            $this->getDescription(),
            $this->getInternalCode()->getStatusCode(),
        );
    }

}
