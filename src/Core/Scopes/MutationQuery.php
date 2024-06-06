<?php

namespace Core\Scopes;

use Core\MutationData;

trait MutationQuery
{
    public function createWithInput(MutationData $mutationInput)
    {
        return $this->create($mutationInput->getData());
    }
}
