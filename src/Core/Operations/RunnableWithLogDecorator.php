<?php

namespace Core\Operations;

use Illuminate\Support\Facades\Log;

class RunnableWithLogDecorator extends RunnableDecorator
{
    public function __construct(RunnableInterface $action)
    {
        parent::__construct($action);
    }

    public function run(...$args)
    {
        Log::info('Action started: '.get_class($this->action));

        return parent::run(...$args);
    }
}
