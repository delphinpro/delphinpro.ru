<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace App\Orchid\Screens\Settings;

use App\Orchid\Helpers\ButtonSave;
use App\Orchid\Screens\Settings\Layouts\SettingsMenu;
use App\Services\Settings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Code;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class GeneralSettingsScreen extends Screen
{
    public function query(Settings $settings): iterable
    {
        return [
            'settings' => $settings,
        ];
    }

    public function name(): ?string { return 'Настройки'; }

    public function description(): ?string { return 'Общие'; }

    public function commandBar(): iterable
    {
        return [
            ButtonSave::make(),
        ];
    }

    public function layout(): iterable
    {
        return [
            SettingsMenu::class,
            Layout::rows([
                Switcher::make('settings.enableAnalytics')->placeholder('Включить код аналитики')->sendTrueOrFalse(),
                Code::make('settings.analyticsCode')
                    ->title('Код счетчиков и аналитики')
                    ->language(Code::MARKUP)
                    ->style('font-size:20px;')
                    ->rows(12),
            ]),
            Layout::rows([
                Switcher::make('settings.displayComments')->placeholder('Показывать комментарии')->sendTrueOrFalse(),
                Switcher::make('settings.enableComments')->placeholder('Разрешить комментировать')->sendTrueOrFalse(),
            ])->title('Комментарии'),
        ];
    }

    public function save(Request $request, Settings $settings): RedirectResponse
    {
        foreach ($request->input('settings') as $key => $value) {
            $settings[$key] = $value;
        }

        $settings->save();

        Toast::info('Настройки сохранены');

        return back();
    }
}
