<?php

namespace Modules\User\Actions;

use Core\Operations\AsAction;

class CreateAdminAction
{
    use AsAction;

    protected array $access = [
        'roles' => ['admin'],
        'permissions' => ['create-admin'],
        'can' => ['create-admin'],
    ];
}
