<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace App\Orchid\Screens\Settings\Layouts;

use Orchid\Screen\Actions\Menu;
use Orchid\Screen\Layouts\TabMenu;

class SettingsMenu extends TabMenu
{
    protected function navigations(): iterable
    {
        return [
            Menu::make('Общие')->route('platform.settings.general'),
            Menu::make('Пользовательские')->route('platform.settings.user'),
        ];
    }
}
