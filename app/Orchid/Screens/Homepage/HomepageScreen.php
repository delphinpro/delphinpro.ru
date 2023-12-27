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

class HomepageScreen extends Screen
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
                    ->max(255),

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

                CheckBox::make('articles.strip')
                    ->placeholder('Strip color')
                    ->sendTrueOrFalse(),

                Input::make('articles.title')
                    ->title('Заголовок')
                    ->type('text')
                    ->max(255),

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
            'intro.title'      => 'string|nullable',
            'intro.subtitle'   => 'string|nullable',
            'intro.background' => 'int|nullable',

            'articles.enabled'  => 'required|bool',
            'articles.strip'    => 'required|bool',
            'articles.title'    => 'string|nullable',
            'articles.subtitle' => 'string|nullable',
            'articles.count'    => 'int|required|min:1',
        ]);

        $this->updateVar('intro', $validated['intro']);
        $this->updateVar('aboutMe', $validated['about']);
        $this->updateVar('lastArticles', $validated['articles']);

        Toast::info('Сохранено');

        return redirect()->route('platform.homepage');
    }

    private function updateVar(string $name, array $var, array $casts = []): void
    {
        $casts = array_merge([
            'enabled'    => 'bool',
            'strip'      => 'bool',
            'background' => 'int|null',
            'count'      => 'int',
        ], $casts);

        foreach ($var as $key => $value) {
            $var[$key] = match ($casts[$key] ?? 'string') {
                'bool'     => (bool)(int)$value,
                'int'      => (int)$value,
                'int|null' => $value === null ? $value : (int)$value,
                default    => $value,
            };
        }

        Variable::findOrNew($name)->fill([
            'name'  => $name,
            'value' => $var,
        ])->save();
    }
}
