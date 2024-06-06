<?php

namespace Core\Middlewares;

use Closure;
use Core\Enums\AppLocale;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class LocalizeMiddleware
{
    protected const ACCEPT_LANGUAGE_HEADER = 'Accept-Language';

    public function handle(Request $request, Closure $next): Response
    {
        $lang = $request->header(self::ACCEPT_LANGUAGE_HEADER);
        if ($lang && Arr::has(AppLocale::getValues(), $lang)) {
            app()->setLocale($request->header(self::ACCEPT_LANGUAGE_HEADER));
        } else {
            app()->setLocale('en');
        }

        return $next($request);
    }
}
