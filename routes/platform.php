<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

declare(strict_types=1);

use App\Http\Middleware\VerifyCsrfToken;
use App\Orchid\Controllers\ArticleController;
use App\Orchid\Controllers\UploadController;
use App\Orchid\Screens\Article\ArticleEditScreen;
use App\Orchid\Screens\Article\ArticleListScreen;
use App\Orchid\Screens\Article\ArticleTrashScreen;
use App\Orchid\Screens\Examples as Ex;
use App\Orchid\Screens\Homepage\Homepage;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\Settings\GeneralSettingsScreen;
use App\Orchid\Screens\Settings\UserSettingsScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

Route::screen('homepage', Homepage::class)->name('platform.homepage');

Route::screen('settings/general', GeneralSettingsScreen::class)->name('platform.settings.general');
Route::screen('settings/user', UserSettingsScreen::class)->name('platform.settings.user');

Route::post('articles/{article}/save', [ArticleController::class, 'saveContent'])->name('platform.article.saveContent');
Route::screen('articles/{article}/edit', ArticleEditScreen::class)->name('platform.article.edit');
Route::screen('articles/create', ArticleEditScreen::class)->name('platform.article.create');
Route::screen('articles/trash', ArticleTrashScreen::class)->name('platform.article.trash');
Route::screen('articles', ArticleListScreen::class)->name('platform.article.list');

Route::screen('profile', UserProfileScreen::class)->name('platform.profile');
Route::screen('users/{user}/edit', UserEditScreen::class)->name('platform.systems.users.edit');
Route::screen('users/create', UserEditScreen::class)->name('platform.systems.users.create');
Route::screen('users', UserListScreen::class)->name('platform.systems.users');
Route::screen('roles/{role}/edit', RoleEditScreen::class)->name('platform.systems.roles.edit');
Route::screen('roles/create', RoleEditScreen::class)->name('platform.systems.roles.create');
Route::screen('roles', RoleListScreen::class)->name('platform.systems.roles');

Route::post('upload', [UploadController::class, 'upload'])->name('image.upload')
    ->withoutMiddleware(VerifyCsrfToken::class);

Route::screen('example', Ex\ExampleScreen::class)->name('platform.example');

Route::screen('/examples/form/fields', Ex\ExampleFieldsScreen::class)->name('platform.example.fields');
Route::screen('/examples/form/advanced', Ex\ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');
Route::screen('/examples/form/editors', Ex\ExampleTextEditorsScreen::class)->name('platform.example.editors');
Route::screen('/examples/form/actions', Ex\ExampleActionsScreen::class)->name('platform.example.actions');

Route::screen('/examples/layouts', Ex\ExampleLayoutsScreen::class)->name('platform.example.layouts');
Route::screen('/examples/grid', Ex\ExampleGridScreen::class)->name('platform.example.grid');
Route::screen('/examples/charts', Ex\ExampleChartsScreen::class)->name('platform.example.charts');
Route::screen('/examples/cards', Ex\ExampleCardsScreen::class)->name('platform.example.cards');
