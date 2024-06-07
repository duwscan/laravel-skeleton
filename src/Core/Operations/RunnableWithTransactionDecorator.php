<?php

namespace Core\Operations;

use Illuminate\Support\Facades\DB;

class RunnableWithTransactionDecorator extends RunnableDecorator
{
    public function __construct(RunnableInterface $action)
    {
        parent::__construct($action);
    }
    public function run(...$args)
    {
        return DB::transaction(function () use ($args) {
            return parent::run(...$args);
        });
    }
}
