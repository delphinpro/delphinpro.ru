<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Orchid\Helpers;

use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;

class ButtonDelete
{
    public static function make(string $title = 'Удалить'): Button
    {
        return Button::make($title)
            ->icon('bs.x-lg')
            ->type(Color::DANGER)
            ->method('delete');
    }
}
