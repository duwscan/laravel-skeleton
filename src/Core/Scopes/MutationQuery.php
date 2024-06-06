<?php

namespace Core\Scopes;

use Core\Data\MutationData;

trait MutationQuery
{
    public function createWithInput(MutationData $mutationInput)
    {
        return $this->create($mutationInput->getData());
    }
}
