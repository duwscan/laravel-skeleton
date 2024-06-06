<?php

namespace Core\Traits;

use Illuminate\Support\Facades\Schema;

trait HasTableExistColumn
{
    protected function hasColumn(string $column): bool
    {
        return Schema::hasColumn($this->getTable(), $column);
    }
}
