<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace Modules\OrchidExamples\Providers;

use App\Providers\ModulesServiceProvider as ServiceProvider;

class OrchidExamplesServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'OrchidExamples';

    protected string $moduleNameLower = 'orchidexamples';

    public function boot(): void
    {
        $this->registerViews();
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
