<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Http\Controllers;

use App\Models\Variable;
use Orchid\Attachment\Models\Attachment;

class HomepageController extends Controller
{
    public function __invoke()
    {
        $intro = Variable::find('intro')?->value ?? [];
        if ($media = Attachment::find($intro['background'] ?? null)) {
            $intro['background'] = $media->url;
        }

        $aboutMe = Variable::find('about_me')?->value ?? [];

        return view('pages.welcome', compact('intro', 'aboutMe'));
    }
}
