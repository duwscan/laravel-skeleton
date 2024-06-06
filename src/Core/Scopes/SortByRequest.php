<?php

namespace Core\Scopes;

use Core\Traits\hasTableExistColumn;
use http\Env\Request;

trait SortByRequest
{
    use hasTableExistColumn;

    protected string $sortByRequestParam = 'sort_by';
    protected string $sortDirectionRequestParam = 'sort_direction';
    const DEFAULT_SORT_DIRECTION = 'asc';
    const DIRECTIONS = ['asc', 'desc'];

    public function sortByRequest()
    {
        $sortBy = request()->get($this->sortByRequestParam);
        $sortDirection = request()->get($this->sortDirectionRequestParam, self::DEFAULT_SORT_DIRECTION);
        if($this->hasColumn($sortBy) && in_array($sortDirection, self::DIRECTIONS)) {
            return $this->orderBy($sortBy, $sortDirection);
        }else{
            $defaultKey = $this->getKey() ?? 'id';
            $defaultSortColumn = $this->hasColumn('created_at') ? 'created_at' : $defaultKey;
            $defaultSortOrder = $defaultSortColumn === $defaultKey ? 'asc' : 'desc';
            return $this->orderBy($defaultSortColumn, $defaultSortOrder);
        }
    }
}
