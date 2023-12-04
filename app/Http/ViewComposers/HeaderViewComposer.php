<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class HeaderViewComposer
{
    public function compose(View $view): void
    {
        $view->with('mainmenu', [
            [
                'title'       => 'Главная',
                'link'        => route('home'),
                'activeClass' => active('home'),
            ],
            [
                'title'       => 'Публикации',
                'link'        => route('article.index'),
                'activeClass' => active('article.*'),
            ],
        ]);
    }
}
