<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace App\Orchid\Screens\Settings;

use App\Orchid\Helpers\ButtonSave;
use App\Orchid\Helpers\Display;
use App\Orchid\Screens\Settings\Layouts\SettingsMenu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class UserSettingsScreen extends Screen
{
    public function query(): iterable
    {
        return [];
    }

    public function name(): ?string
    {
        return 'Настройки';
    }

    public function description(): ?string
    {
        return 'Пользовательские';
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
                Group::make([
                    Select::make('user.timezone')->title('Часовой пояс')
                        ->options(getTimeZoneList())
                        ->value(getUserTimeZone()),
                    Label::make('')->title('Текущее время')
                        ->value(new HtmlString(Display::datetime(now()->timezone(getUserTimeZone())))),
                ]),
            ]),
        ];
    }

    public function save(Request $request): RedirectResponse
    {
        $us = ($user = $request->user())->settings;

        $us['timezone'] = ($timezone = $request->input('user.timezone')) === config('app.timezone') ? null : $timezone;

        $user->settings = $us;
        $user->save();

        Toast::info('Настройки сохранены');

        return back();
    }
}
