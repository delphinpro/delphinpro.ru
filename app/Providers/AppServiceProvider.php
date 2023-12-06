<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2019-2023.
 */

namespace App\Providers;

use App\Models\Article;
use App\Observers\ArticleObserver;
use App\Services\Settings;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

/**
 * @property \Illuminate\Contracts\Foundation\Application $app
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Settings::class, fn() => new Settings(storage_path('app/settings.php')));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('pagination::bootstrap-5');
        Article::observe(ArticleObserver::class);
    }
}
