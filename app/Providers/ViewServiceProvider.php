<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Providers;

use App\Http\ViewComposers\HeaderViewComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        View::composer('partials.app.header', HeaderViewComposer::class);
    }
}
