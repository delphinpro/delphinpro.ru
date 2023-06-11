<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

use Illuminate\Support\Facades\Route;
use Modules\OrchidExamples\Screens\ExampleActionsScreen;
use Modules\OrchidExamples\Screens\ExampleCardsScreen;
use Modules\OrchidExamples\Screens\ExampleChartsScreen;
use Modules\OrchidExamples\Screens\ExampleFieldsAdvancedScreen;
use Modules\OrchidExamples\Screens\ExampleFieldsScreen;
use Modules\OrchidExamples\Screens\ExampleLayoutsScreen;
use Modules\OrchidExamples\Screens\ExampleScreen;
use Modules\OrchidExamples\Screens\ExampleTextEditorsScreen;

Route::screen('example', ExampleScreen::class)->name('platform.example');

Route::screen('example/form/basic', ExampleFieldsScreen::class)->name('platform.example.fields');
Route::screen('example/form/advanced', ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');
Route::screen('example/form/editors', ExampleTextEditorsScreen::class)->name('platform.example.editors');
Route::screen('example/form/actions', ExampleActionsScreen::class)->name('platform.example.actions');

Route::screen('example/layouts', ExampleLayoutsScreen::class)->name('platform.example.layouts');
Route::screen('example/charts', ExampleChartsScreen::class)->name('platform.example.charts');
Route::screen('example/cards', ExampleCardsScreen::class)->name('platform.example.cards');
