<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Orchid\Helpers;

use Orchid\Screen\Actions\Link;
use Orchid\Support\Color;

class ButtonEdit
{
    public static function make(string $title = 'Изменить'): Link
    {
        return Link::make($title)
            ->icon('bs.pencil-fill')
            ->type(Color::LIGHT);
    }
}
