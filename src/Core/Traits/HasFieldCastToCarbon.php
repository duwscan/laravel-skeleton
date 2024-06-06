<?php

namespace Core\Traits;

trait HasFieldCastToCarbon
{
    public function isFieldCastToCarbon($field)
    {
        $casts = $this->getCasts();

        return isset($casts[$field]) && $casts[$field] === 'datetime';
    }
}
