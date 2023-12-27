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
 * @property bool   $strip
 * @property string $title
 * @property string $content
 * @property int    $background
 * @property string $backgroundUrl
 */
class AboutMeDTO extends VarDTO
{
    public function created(): void
    {
        if ($media = Attachment::find($this->background)) {
            $this->backgroundUrl = $media->url;
        }
    }
}
