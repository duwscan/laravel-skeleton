<?php

namespace Core\Controllers;

use Core\Responses\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

abstract class ApiController extends BaseController
{
    use ApiResponse, AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function authorizeNestedResource($model, $modelParameter, $parameter = null, array $options = [], $request = null)
    {
        $model = is_array($model) ? implode(',', $model) : $model;

        $parameter = is_array($parameter) ? implode(',', $parameter) : $parameter;

        $parameter = $parameter ?: Str::snake(class_basename($model));

        $middleware = [];

        foreach ($this->resourceAbilityMap() as $method => $ability) {
            $modelName = in_array($method, $this->resourceMethodsWithoutModels()) ? $model.','.$parameter : $model.','.$parameter.','.$modelParameter;
            $middleware["can:{$ability},{$modelName}"][] = $method;
        }
        foreach ($middleware as $middlewareName => $methods) {
            $this->middleware($middlewareName, $options)->only($methods);
        }
    }
}
