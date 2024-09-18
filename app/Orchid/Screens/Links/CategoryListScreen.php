<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2024.
 */

namespace App\Orchid\Screens\Links;

use App\Models\LinkCategory;
use App\Orchid\Helpers\ButtonCreate;
use App\Orchid\Helpers\ButtonEdit;
use App\Orchid\Helpers\LinkPreview;
use App\Services\Settings;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class CategoryListScreen extends Screen
{
    public function query(Settings $settings): iterable
    {
        return [
            'cats' => LinkCategory::withCount('links')->paginate($settings->adminPaginationCount),
        ];
    }

    public function name(): ?string
    {
        return 'Категории ссылок';
    }

    public function commandBar(): iterable
    {
        return [
            LinkPreview::make(route('link.index')),
            ButtonCreate::make('Добавить категорию')->href(route('platform.link-category.create')),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('cats', [
                TD::make('id')->width(100),
                TD::make('title', 'Название')->render(fn(LinkCategory $cat) => $this->getTitle($cat)),
                TD::make('links_count', 'Кол-во ссылок'),
                TD::make('actions', '')->alignRight()
                    ->render(fn(LinkCategory $linkCategory) => ButtonEdit::make()
                        ->href(route('platform.link-category.edit', $linkCategory))),
            ]),
        ];
    }

    private function getTitle(LinkCategory $cat): string
    {
        return '<a href="'.route('platform.link-category.edit', $cat).'" style="font-weight:500">'.$cat->title.'</a>';
    }
}
