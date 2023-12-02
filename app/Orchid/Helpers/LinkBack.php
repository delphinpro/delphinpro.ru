<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Orchid\Helpers;

use Orchid\Screen\Actions\Link;
use Orchid\Support\Color;

class LinkBack
{
    public static function make(string $title = 'Назад'): Link
    {
        return Link::make($title)
            ->type(Color::LIGHT)
            ->href('javascript:history.back()');
    }
}
