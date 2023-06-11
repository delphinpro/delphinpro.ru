<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace Modules\OrchidExamples\Providers;

use App\Providers\ModulesRouteServiceProvider;

class RouteServiceProvider extends ModulesRouteServiceProvider
{
    protected string $moduleNamespace = 'Modules\OrchidExamples\Http\Controllers';

    protected string $moduleName = 'OrchidExamples';

    public function map(): void
    {
        $this->mapAdminRoutes();
    }
}
