<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace App\Orchid\Screens;

use App\Models\Article;
use App\Models\Comment;
use App\Orchid\Helpers\LinkPreview;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
{
    public function query(): iterable
    {
        $totalArticles = Article::count();
        $publishedArticles = Article::where('published', true)->count();
        $totalComments = Comment::count();
        $newComments = Comment::where('published', false)->count();

        return [
            'metrics' => [
                'totalArticles'     => ['value' => number_format($totalArticles)],
                'publishedArticles' => ['value' => number_format($publishedArticles)],
                'totalComments'     => ['value' => number_format($totalComments)],
                'publishedComments' => ['value' => number_format($newComments)],
            ],
        ];
    }

    public function name(): ?string { return 'Панель управления'; }

    public function description(): ?string { return 'delphinpro.ru'; }

    public function commandBar(): iterable
    {
        return [
            LinkPreview::make(route('home')),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::view('platform::partials.update-assets'),

            Layout::metrics([
                'Общее кол-во статей'          => 'metrics.totalArticles',
                'Кол-во опубликованных статей' => 'metrics.publishedArticles',
                'Общее кол-во комментариев'    => 'metrics.totalComments',
                'Кол-во новых комментариев'    => 'metrics.publishedComments',
            ]),
        ];
    }
}
