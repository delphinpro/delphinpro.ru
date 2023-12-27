<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Data\Concrete;

use App\Data\VarDTO;
use App\Models\Article;
use Illuminate\Support\Collection;

/**
 * @property bool                $enabled
 * @property string              $title
 * @property string              $subtitle
 * @property int                 $count
 * @property Collection<Article> $articles
 */
class ArticlesDTO extends VarDTO
{
    public function created(): void
    {
        $this->count = $this->count ?? 3;

        $this->articles = Article::lastPublished()
            ->take($this->count)
            ->get();
    }
}
