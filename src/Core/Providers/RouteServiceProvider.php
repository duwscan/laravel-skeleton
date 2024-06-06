<?php

namespace Core\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as LaravelRouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class RouteServiceProvider extends LaravelRouteServiceProvider
{
    public function map(): void
    {
        $this->loadApiRouting();
    }

    public function loadApiRouting(): void
    {
        // Define the pattern to search for route files
        $pattern = base_path('src/Modules/*/Routing/V*/api.php');

        // Use glob to find all files matching the pattern
        $routeFiles = glob($pattern);

        // Loop through each route file
        foreach ($routeFiles as $routeFile) {
            // Extract the version and module name parts from the path
            // E.g., from src/Modules/ModuleName/Routing/V1/api.php, extract V1 and ModuleName
            preg_match('/src\/Modules\/([^\/]+)\/Routing\/(V\d+)\/api\.php/', str_replace('\\', '/', $routeFile),
                $matches);

            if (isset($matches[1], $matches[2])) {
                $moduleName = strtolower($matches[1]); // Extract the module name
                $moduleName = Str::plural($moduleName); // Convert the module name to plural due to rest api convention
                $version = strtolower($matches[2]); // Extract the version (e.g., v1, v2)
            } else {
                // Skip if the version or module name cannot be determined
                continue;
            }

            // Ensure the version starts with 'v' and is lowercase
            if (! Str::startsWith($version, 'v')) {
                $version = 'v'.$version;
            }

            // Define the API prefix dynamically based on the version and module name
            Route::prefix("api/{$version}/{$moduleName}")
                ->middleware('api')
                ->group($routeFile);
        }
    }
}
