<?php

namespace Core\Exceptions;

use Core\Responses\ApiResponse;
use Illuminate\Foundation\Exceptions\Handler as LaravelExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ExceptionHandler extends LaravelExceptionHandler
{
    use ApiResponse;

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->renderable(
            function (ValidationException $e) {
                return $this->responseError($e->getMessage(), null, ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
            }
        );

        $this->renderable(
            function (UnauthorizedHttpException $e) {
                return $this->responseError(code: ExceptionCode::Unauthorized);
            }
        );

        $this->renderable(
            function (NotFoundHttpException $e) {
                return $this->responseError(code: ExceptionCode::NotFound);
            }
        );

        $this->renderable(
            function (BadRequestHttpException $e) {
                return $this->responseError(code: ExceptionCode::BadRequest);
            }
        );
        if (app()->isLocal()) {
            $this->renderable(
                function (\Exception $e) {
                    return $this->responseError($e->getMessage(), [
                        'line' => $e->getLine(),
                        'file' => $e->getFile(),
                        'trace' => $e->getTrace(),
                        'code' => $e->getCode(),
                    ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
                }
            );
        } else {
            $this->renderable(
                function (\Exception $e) {
                    return $this->responseError(code: ExceptionCode::InternalServerError);
                }
            );

        }
    }
}
