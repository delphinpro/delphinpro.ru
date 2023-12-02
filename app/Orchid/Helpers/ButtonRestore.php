<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Orchid\Helpers;

use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;

class ButtonRestore
{
    public static function make(string $title = 'Восстановить'): Button
    {
        return Button::make($title)
            ->icon('bs.arrow-repeat')
            ->type(Color::SUCCESS)
            ->method('restore')
            ->confirm('Восстановить из корзины?');
    }
}
