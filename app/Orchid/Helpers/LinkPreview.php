<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Orchid\Helpers;

use Orchid\Screen\Actions\Link;
use Orchid\Support\Color;

class LinkPreview
{
    public static function make(string $route, string $title = ''): Link
    {
        return Link::make($title)
            ->type(Color::LIGHT)
            ->icon('bs.display')
            ->target('_black')
            ->href($route);
    }
}
