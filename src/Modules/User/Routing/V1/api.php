<?php

use Illuminate\Support\Facades\Route;

Route::get('v1-user-api', function () {
    throw new Exception("test");
});
