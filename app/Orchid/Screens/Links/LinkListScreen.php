<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2024.
 */

namespace App\Orchid\Screens\Links;

use App\Models\Link;
use App\Orchid\Helpers\ButtonCreate;
use App\Orchid\Helpers\ButtonEdit;
use App\Orchid\Helpers\Display;
use App\Services\Settings;
use Illuminate\Support\Str;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class LinkListScreen extends Screen
{
    public function query(Settings $settings): iterable
    {
        return [
            'links' => Link::paginate($settings->adminPaginationCount),
        ];
    }

    public function name(): ?string
    {
        return 'Полезные ссылки';
    }

    public function commandBar(): iterable
    {
        return [
            ButtonCreate::make('Добавить ссылку')->href(route('platform.link.create')),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('links', [
                TD::make('id'),
                TD::make('title', 'Название')->render(fn(Link $link) => $this->getTitle($link)),
                TD::make('url', 'Ссылка')->render(fn(Link $link) => $this->getUrl($link)),
                TD::make('published', 'Опубликовано')->sort()
                    ->render(fn(Link $link) => Display::bool($link->published)),
                TD::make('actions', '')->alignRight()
                    ->render(fn(Link $link) => ButtonEdit::make()
                        ->href(route('platform.link.edit', $link))),
            ]),
        ];
    }

    private function getTitle(Link $link): string
    {
        $truncatedTitle = Str::limit($link->title, 50);

        if (!$link->published) {
            $title = '<i class="text-muted">'.$truncatedTitle.'</i>';
        } else {
            $title = '<span style="font-weight:500">'.$truncatedTitle.'</span>';
        }

        return '<a href="'.route('platform.link.edit', $link).'">'.$title.'</a>';

    }

    private function getUrl(Link $link): string
    {
        $truncatedTitle = Str::limit($link->url, 50);

        if (!$link->published) {
            $title = '<i class="text-muted">'.$truncatedTitle.'</i>';
        } else {
            $title = '<span style="font-weight:400">'.$truncatedTitle.'</span>';
        }

        return '<a href="'.$link->url.'" target="_blank">'.$title.'</a>';

    }
}
