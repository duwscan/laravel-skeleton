<?php

namespace Modules\User\Exceptions;

use Core\Exceptions\ExceptionCode;
use Core\Exceptions\InternalException;

class UserException extends InternalException
{
    public static function userAlreadyExists(): self
    {
        return static::new(
            ExceptionCode::UserAlreadyExists,
        );
    }
}
