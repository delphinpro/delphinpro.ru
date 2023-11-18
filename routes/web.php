<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2019-2023.
 */

use App\Http\Controllers\HomepageController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomepageController::class)->name('home');
