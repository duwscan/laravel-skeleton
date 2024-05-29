<?php

namespace Modules\User\Builder;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class UserQueryBuilder extends Builder
{
    public function __construct(QueryBuilder $query)
    {
        parent::__construct($query);
    }
}
