<?php

use Illuminate\Support\Facades\Route;

Route::get('v2-api', function () {
    throw  \Modules\User\Exceptions\UserException::userAlreadyExists();
});
