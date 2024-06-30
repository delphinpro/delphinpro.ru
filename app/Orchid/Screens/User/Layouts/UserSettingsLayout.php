<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace App\Orchid\Screens\User\Layouts;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class UserSettingsLayout extends Rows
{
    public function fields(): array
    {
        return [
            Input::make('user.trust_level')
                ->type('text')
                ->max(2147483647)
                ->min(-2147483647)
                ->required()
                ->title('Уровень доверия'),
        ];
    }
}
