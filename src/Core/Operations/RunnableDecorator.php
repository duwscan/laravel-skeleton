<?php

namespace Core\Operations;

class RunnableDecorator implements RunnableInterface
{
    use AsRunnable;
    protected RunnableInterface $action;

    public function __construct(RunnableInterface $action)
    {
        $this->action = $action;
    }

    public function run(...$args)
    {
        return $this->action->run(...$args);
    }
}
