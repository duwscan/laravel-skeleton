<?php

namespace Core\Operations;

use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsObject;

trait AsRunnable
{
    use AsObject;

    public function runWithTransaction($retry, ...$args)
    {
        return DB::transaction(function () use ($args) {
            return $this->run(...$args);
        }, $retry);
    }
}
