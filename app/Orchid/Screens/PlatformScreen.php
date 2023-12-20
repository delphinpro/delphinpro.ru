<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Article;
use App\Orchid\Helpers\LinkPreview;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $articlesCount = Article::count();

        return [
            'metrics' => [
                'articles' => ['value' => number_format($articlesCount)],
            ],
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string { return 'Панель управления'; }

    /**
     * Display header description.
     */
    public function description(): ?string { return 'delphinpro.ru'; }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            LinkPreview::make(route('home')),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::view('platform::partials.update-assets'),

            Layout::metrics([
                'Кол-во публикаций' => 'metrics.articles',
            ]),
        ];
    }
}
