<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2019-2024.
 */

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\HomepageController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomepageController::class)->name('home');
Route::get('a', [ArticleController::class, 'index'])->name('article.index');
Route::get('a/tags', [ArticleController::class, 'tags'])->name('article.tags');
Route::get('a/tags/{tag:name}', [ArticleController::class, 'byTag'])->name('article.by_tag');
Route::get('a/{article}', [ArticleController::class, 'show'])->name('article.show');
