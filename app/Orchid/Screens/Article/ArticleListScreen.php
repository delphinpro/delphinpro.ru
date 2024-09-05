<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace App\Orchid\Screens\Article;

use App\Models\Article;
use App\Orchid\Helpers\ButtonCreate;
use App\Orchid\Helpers\ButtonEdit;
use App\Orchid\Helpers\Display;
use App\Orchid\Helpers\LinkPreview;
use App\Services\Settings;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class ArticleListScreen extends Screen
{
    public function query(Settings $settings): iterable
    {
        return [
            'articles' => Article::filters()
                ->defaultSort('created_at', 'desc')
                ->paginate($settings->adminPaginationCount),
        ];
    }

    public function name(): ?string
    {
        return 'Публикации';
    }

    public function commandBar(): iterable
    {
        return [
            LinkPreview::make(route('article.index')),
            Link::make('Корзина')
                ->type(Color::LIGHT)
                ->icon('bs.trash')
                ->href(route('platform.article.trash'))
                ->canSee(Article::onlyTrashed()->count()),

            ButtonCreate::make('Написать')->href(route('platform.article.create')),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('articles', [
                TD::make('id'),
                TD::make('title', 'Заголовок')->sort()
                    ->render(fn(Article $article) => $this->getTitle($article)),
                TD::make('published', 'Опубликовано')->sort()
                    ->render(fn(Article $article) => Display::bool($article->published)),
                TD::make('created_at', 'Дата создания')->sort()
                    ->render(fn(Article $article) => Display::datetime($article->local_created_at)),
                TD::make('updated_at', 'Дата обновления')->sort()
                    ->render(fn(Article $article) => Display::datetime($article->local_updated_at)),
                TD::make('actions', '')->alignRight()
                    ->render(fn(Article $article) => ButtonEdit::make()
                        ->href(route('platform.article.edit', $article))),
            ]),
        ];
    }

    private function getTitle(Article $article): string
    {
        $truncatedTitle = Str::limit($article->title, 80);

        if ($article->deleted_at) {
            $title = '<s class="text-danger">'.$truncatedTitle.'</s>';
        } elseif (!$article->published) {
            $title = '<i class="text-muted">'.$truncatedTitle.'</i>';
        } else {
            $title = '<span style="font-weight:500">'.$truncatedTitle.'</span>';
        }

        return '<a href="'.route('platform.article.edit', $article).'">'.$title.'</a>';

    }
}
