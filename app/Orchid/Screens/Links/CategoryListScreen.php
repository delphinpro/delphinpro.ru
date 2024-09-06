<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2024.
 */

namespace App\Orchid\Screens\Links;

use App\Models\LinkCategory;
use App\Orchid\Helpers\ButtonCreate;
use App\Orchid\Helpers\ButtonEdit;
use App\Services\Settings;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class CategoryListScreen extends Screen
{
    public function query(Settings $settings): iterable
    {
        return [
            'cats' => LinkCategory::paginate($settings->adminPaginationCount),
        ];
    }

    public function name(): ?string
    {
        return 'Категории ссылок';
    }

    public function commandBar(): iterable
    {
        return [
            ButtonCreate::make('Добавить категорию')->href(route('platform.link-category.create')),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('cats', [
                TD::make('id')->width(100),
                TD::make('title', 'Название'),
                TD::make('actions', '')->alignRight()
                    ->render(fn(LinkCategory $linkCategory) => ButtonEdit::make()
                        ->href(route('platform.link-category.edit', $linkCategory))),
            ]),
        ];
    }
}
