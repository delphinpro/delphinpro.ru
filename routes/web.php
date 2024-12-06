<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2019-2024.
 */

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\LinkController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomepageController::class)->name('home');

/*-------------------------------------------------------------------------
| Articles
|------------------------------------------------------------------------*/

Route::get('a', [ArticleController::class, 'index'])->name('article.index');
Route::get('a/tags', [ArticleController::class, 'tags'])->name('article.tags');
Route::get('a/tags/{tag:name}', [ArticleController::class, 'byTag'])->name('article.by_tag');
Route::get('a/{article}', [ArticleController::class, 'show'])->name('article.show')->whereNumber('article');

/*-------------------------------------------------------------------------
| Article Comments
|------------------------------------------------------------------------*/

Route::post('comments/preview', [CommentController::class, 'preview'])
    ->name('article.comments.preview');
Route::post('comments/{comment}/publish', [CommentController::class, 'moderate'])
    ->name('article.comments.publish');

Route::resource('article.comments', CommentController::class)
    ->names('article.comments')
    ->only(['store', 'destroy'])
    ->shallow();

/*-------------------------------------------------------------------------
| Useful Links
|------------------------------------------------------------------------*/

Route::prefix('links')->group(function () {
    Route::get('/', [LinkController::class, 'index'])->name('link.index');
    Route::get('categories', [LinkController::class, 'categories'])->name('link.categories');
    Route::get('links', [LinkController::class, 'links'])->name('link.links');
});
