<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Orchid\Screens\Homepage;

use App\Models\Variable;
use App\Orchid\Helpers\ButtonSave;
use App\Orchid\Helpers\LinkPreview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class Homepage extends Screen
{
    public function query(): iterable
    {
        return [
            'intro'    => Variable::find('intro')?->value ?? [],
            'articles' => Variable::find('lastArticles')?->value ?? [],
        ];
    }

    public function name(): ?string
    {
        return 'Главная страница';
    }

    public function commandBar(): iterable
    {
        return [
            LinkPreview::make(route('home')),
            ButtonSave::make(),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::block(Layout::rows([
                CheckBox::make('intro.enabled')
                    ->placeholder('Отображать эту секцию')
                    ->sendTrueOrFalse(),

                Input::make('intro.title')
                    ->title('Заголовок')
                    ->type('text')
                    ->max(255)
                    ->required(),

                Input::make('intro.subtitle')
                    ->title('Строка текста')
                    ->type('text')
                    ->max(255),

                Picture::make('intro.background')
                    ->title('Фоновое изображение')
                    ->groups('intro.background')
                    ->storage('public')
                    ->targetId(),
            ]))->title('Вступительная секция'),

            Layout::block(Layout::rows([
                CheckBox::make('articles.enabled')
                    ->placeholder('Отображать эту секцию')
                    ->sendTrueOrFalse(),

                Input::make('articles.title')
                    ->title('Заголовок')
                    ->type('text')
                    ->max(255)
                    ->required(),

                Input::make('articles.subtitle')
                    ->title('Строка текста')
                    ->type('text')
                    ->max(255),

                Input::make('articles.count')
                    ->title('Количество статей')
                    ->type('number')
                    ->max(9),
            ]))->title('Последние статьи'),
        ];
    }

    public function save(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'intro.enabled'    => 'required|bool',
            'intro.title'      => 'required|string',
            'intro.subtitle'   => 'string|nullable',
            'intro.background' => 'int|nullable',

            'articles.enabled'  => 'required|bool',
            'articles.title'    => 'required|string',
            'articles.subtitle' => 'string|nullable',
            'articles.count'    => 'int|required|min:1',
        ]);


        $intro = [
            'enabled'    => (bool)(int)$validated['intro']['enabled'],
            'title'      => $validated['intro']['title'],
            'subtitle'   => $validated['intro']['subtitle'],
            'background' => $validated['intro']['background'] ?? null,
        ];

        Variable::findOrNew('intro')->fill([
            'name'  => 'intro',
            'value' => $intro,
        ])->save();

        $articles = [
            'enabled'  => (bool)(int)$validated['articles']['enabled'],
            'title'    => $validated['articles']['title'],
            'subtitle' => $validated['articles']['subtitle'],
            'count'    => (int)$validated['articles']['count'],
        ];

        Variable::findOrNew('lastArticles')->fill([
            'name'  => 'lastArticles',
            'value' => $articles,
        ])->save();

        Toast::info('Сохранено');

        return redirect()->route('platform.homepage');
    }
}
