<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Orchid\Helpers;

use Orchid\Screen\Actions\Link;
use Orchid\Support\Color;

class ButtonCreate
{
    public static function make(string $title): Link
    {
        return Link::make($title)
            ->icon('bs.plus-lg')
            ->type(Color::PRIMARY)
            ->href(route('platform.article.create'));
    }
}
