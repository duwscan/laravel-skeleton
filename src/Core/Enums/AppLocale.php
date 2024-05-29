<?php

namespace Core\Enums;

enum AppLocale: string
{
    case EN = 'en';

    public static function getValues(): array
    {
        return [
            self::EN,
        ];
    }
}
