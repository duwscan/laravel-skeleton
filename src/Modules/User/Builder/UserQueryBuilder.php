<?php

namespace Modules\User\Builder;

use Core\Scopes\MutationQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class UserQueryBuilder extends Builder
{
    use MutationQuery;

    public function __construct(QueryBuilder $query)
    {
        parent::__construct($query);
    }
}
