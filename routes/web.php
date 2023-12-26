<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2019-2023.
 */

use App\Http\Controllers as C;
use Illuminate\Support\Facades\Route;

Route::get('/', C\HomepageController::class)->name('home');
Route::get('a', [C\ArticleController::class, 'index'])->name('article.index');
Route::get('a/tags', [C\ArticleController::class, 'tags'])->name('article.tags');
Route::get('a/tags/{tag:name}', [C\ArticleController::class, 'byTag'])->name('article.by_tag');
Route::get('a/{article}', [C\ArticleController::class, 'show'])->name('article.show');
