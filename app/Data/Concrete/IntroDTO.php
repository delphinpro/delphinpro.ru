<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Data\Concrete;

use App\Data\VarDTO;
use Orchid\Attachment\Models\Attachment;

/**
 * @property bool   $enabled
 * @property int    background
 * @property string backgroundUrl
 * @property string title
 * @property string subtitle
 */
class IntroDTO extends VarDTO
{
    public function created(): void
    {
        if ($media = Attachment::find($this->background)) {
            $this->backgroundUrl = $media->url;
        }
    }
}
