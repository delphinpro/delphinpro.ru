<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2019-2024.
 */

namespace App\Providers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use App\Observers\ArticleObserver;
use App\Services\Settings;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Orchid\Support\Facades\Dashboard;

/**
 * @property \Illuminate\Contracts\Foundation\Application $app
 */
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Settings::class, fn() => new Settings(storage_path('app/settings.php')));
    }

    public function boot(): void
    {
        Dashboard::useModel(\Orchid\Platform\Models\User::class, User::class);

        Paginator::defaultView('pagination::bootstrap-5');
        Article::observe(ArticleObserver::class);

        Relation::enforceMorphMap([
            'article' => 'App\Models\Article',
            'user'    => 'App\Models\User',
        ]);

        Gate::define('comment.moderate', static fn(User $user) => $user->isAdmin());
        Gate::define('comment.delete', static function (User $user, Comment $comment) {
            return $user->isAdmin() || $user->id === $comment->user_id;
        });
    }
}
