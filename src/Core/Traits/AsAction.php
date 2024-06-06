<?php

namespace Core\Traits;

use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsCommand;
use Lorisleiva\Actions\Concerns\AsJob;
use Lorisleiva\Actions\Concerns\AsListener;
use Symfony\Component\Finder\Exception\AccessDeniedException;

trait AsAction
{
    use AsRunnable;
    use AsListener;
    use AsJob;
    use AsCommand;

    protected array $access;

    public static function make()
    {
        $action = app(static::class);
        if (!$action->authorize()) {
            throw new AccessDeniedException();
        };
        return $action;
    }

    public function runWithTransaction($retry, ...$args)
    {
        return DB::transaction(function () use ($retry, $args) {
            return $this->run(...$args);
        }, $retry);
    }

    public function authorize()
    {
        if (method_exists($this, 'beforeAuthorize')) {
            $this->beforeAuthorize();
        }

        if (!$this->access) {
            return true;
        }
        if (!auth()->check()) {
            return false;
        }

        if (!$this->isValidAccessSchema()) {
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
