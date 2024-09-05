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
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class SystemSettingsScreen extends Screen
{
    public function query(Settings $settings): iterable
    {
        return [
            'settings' => $settings,
        ];
    }

    public function name(): ?string
    {
        return 'Настройки';
    }

    public function description(): ?string
    {
        return 'Системные';
    }

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
                Input::make('settings.adminPaginationCount')->title('Кол-во элементов в списках админки'),
            ]),
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
