<?php

use Core\Providers\AppServiceProvider;
use Core\Providers\RouteServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

return [
    AppServiceProvider::class,
    RouteServiceProvider::class,
    PermissionServiceProvider::class,
];
