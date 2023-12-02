<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Orchid\Helpers;

use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;

class ButtonSave
{
    public static function make(string $title = 'Сохранить'): Button
    {
        return Button::make($title)
            ->type(Color::SUCCESS)
            ->icon('bs.floppy')
            ->method('save');
    }
}
