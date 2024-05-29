<?php

namespace Core\Requests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

abstract class MutationRequest extends FormRequest
{
    abstract public function toModel(): Model;
}
