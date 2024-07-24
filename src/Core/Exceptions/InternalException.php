<?php

namespace Core\Exceptions;

use Core\Responses\ApiResponse;
use Exception;
use Illuminate\Http\Request;

class InternalException extends Exception
{
    use ApiResponse;

    protected string $description;
    protected int $statusCode;
    protected ?\Throwable $replacedException;
    protected ExceptionCode $internalCode;

    public static function new(
        ExceptionCode $code,
        ?string       $message = null,
        ?string       $description = null,
        ?int          $statusCode = null,
        ?\Throwable   $replacedException = null,
    ): static
    {
        $exception = new static(
            $message ?? $code->getMessage(),
            $statusCode ?? $code->getStatusCode(),
        );
        $exception->replacedException = $replacedException;
        $exception->internalCode = $code;
        if ($message === null) {
            $exception->message = $code->getMessage();
        }
        if ($statusCode === null) {
            $exception->statusCode = $code->getStatusCode();
        }
        if ($description === null) {
            $exception->description = $code->getDescription();
        }
        return $exception;
    }

    public function getInternalCode(): ExceptionCode
    {
        return $this->internalCode;
    }

    public function getDescription(): string|array
    {
        if ($this->replacedException) {
            return
                [
                    "message" => $this->replacedException->getMessage(),
                    "trace" => $this->replacedException->getTrace(),
                ];
        }
        return $this->description;
    }

    public function report(): void
    {
        // ...
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
