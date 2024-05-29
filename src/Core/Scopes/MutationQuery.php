<?php

namespace Core\Scopes;

use Core\DataTransferObjects\MutationInput;

trait MutationQuery
{
    public function createWithInput(MutationInput $mutationInput)
    {
        return $this->create($mutationInput->mutationData());
    }
}
