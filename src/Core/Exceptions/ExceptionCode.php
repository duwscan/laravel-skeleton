<?php

namespace Core\Exceptions;

enum ExceptionCode: int
{
    case NoSubscription = 10_000;
    case LimitExceeded = 10_001;
    case UserAlreadyExists = 11_000;
    case NoAccess = 90_000;
    case NotFound = 40_004;
    case InvalidCredentials = 40_002;
    case ServiceUnavailable = 50_003;
    case BadRequest = 40_000;
    case InternalServerError = 50_000;
    case Unauthorized = 40_001;
    case Forbidden = 40_003;

    public function getStatusCode(): int
    {
        $value = $this->value;

        return match (true) {
            $value >= 90_000 => 403,
            $value >= 40_000 => 404,
            $value >= 10_000 => 400,
            default => 500,
        };
    }

    public function getMessage(): string
    {
        $key = "exceptions.{$this->value}.message";
        $translation = __($key);

        if ($key === $translation) {
            return 'Something went wrong';
        }

        return $translation;
    }

    public function getDescription(): string
    {
        $key = "exceptions.{$this->value}.description";
        $translation = __($key);

        if ($key === $translation) {
            return 'No additional description provided';
        }

        return $translation;
    }
}
