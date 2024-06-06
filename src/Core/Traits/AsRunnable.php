<?php

namespace Core\Traits;

use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsObject;

trait AsRunnable
{
    use AsObject;
    public function runWithTransaction($retry, ...$args)
    {
        return DB::transaction(function () use ($retry, $args) {
            return $this->run(...$args);
        },$retry);
    }
}
