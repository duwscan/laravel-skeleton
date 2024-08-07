<?php

namespace Core\Operations;

use Core\Exceptions\ExceptionCode;
use Core\Exceptions\InternalException;
use Lorisleiva\Actions\Concerns\AsCommand;
use Lorisleiva\Actions\Concerns\AsJob;
use Lorisleiva\Actions\Concerns\AsListener;

trait AsAction
{
    use AsCommand;
    use AsJob;
    use AsListener;
    use AsRunnable;

    protected array $access;

    public function validate(array $data): void
    {
        if (method_exists($this, 'rules')) {
            validator($data, $this->rules());
        }
    }

    public static function make()
    {
        $action = app(static::class);
        if (! $action->authorize()) {
            throw new InternalException(code: ExceptionCode::NoAccess);
        }

        return $action;
    }

    public function authorize()
    {
        if (method_exists($this, 'beforeAuthorize')) {
            $this->beforeAuthorize();
        }

        if (! $this->access) {
            return true;
        }
        if (! auth()->check()) {
            return false;
        }

        if (! $this->isValidAccessSchema()) {
            throw new \Exception('Invalid access schema');
        }

        if (isset($this->access['roles'])) {
            return auth()->user()->hasAnyRole($this->access['roles']);
        }

        if (isset($this->access['permissions'])) {
            return auth()->user()->hasAnyPermission($this->access['permissions']);
        }

        if (isset($this->access['can'])) {
            if (is_array($this->access['can'])) {
                return collect($this->access['can'])->any(function ($can) {
                    return auth()->user()->can($can);
                });
            }

            return auth()->user()->can($this->access['can']);
        }

        return true;
    }

    private function isValidAccessSchema(): bool
    {
        return isset($this->access['roles']) || isset($this->access['permissions']) || isset($this->access['can']);
    }
}
