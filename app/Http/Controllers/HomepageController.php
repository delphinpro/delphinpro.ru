<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Http\Controllers;

use App\Data\Concrete\AboutMeDTO;
use App\Data\Concrete\ArticlesDTO;
use App\Data\Concrete\IntroDTO;

class HomepageController extends Controller
{
    public function __invoke()
    {
        return view('pages.welcome', [
            'intro'    => IntroDTO::make('intro'),
            'aboutMe'  => AboutMeDTO::make('aboutMe'),
            'articles' => ArticlesDTO::make('lastArticles'),
        ]);
    }
}
