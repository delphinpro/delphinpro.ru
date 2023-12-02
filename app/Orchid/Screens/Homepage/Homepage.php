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
            'intro' => Variable::find('intro')?->value ?? [],
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
        ];
    }

    public function save(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'intro.enabled'    => 'required|bool',
            'intro.title'      => 'required|string',
            'intro.subtitle'   => 'string|nullable',
            'intro.background' => 'int|nullable',
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

        Toast::info('Сохранено');

        return redirect()->route('platform.homepage');
    }
}
