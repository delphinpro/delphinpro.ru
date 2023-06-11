<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Orchid\Support\Facades\Dashboard;

class ModulesRouteServiceProvider extends ServiceProvider
{
    protected string $moduleNamespace;

    protected string $moduleName;

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(module_path($this->moduleName, '/Routes/api.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->namespace($this->moduleNamespace)
            ->group(module_path($this->moduleName, '/Routes/web.php'));
    }

    /**
     * Define the "admin" routes for the application.
     */
    protected function mapAdminRoutes(): void
    {
        Route::prefix(Dashboard::prefix('/'))
            ->middleware(config('platform.middleware.private'))
            ->group(module_path($this->moduleName, '/Routes/admin.php'));
    }
}
