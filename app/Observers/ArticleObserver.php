<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Observers;

use App\Models\Article;

class ArticleObserver
{
    /**
     * @throws \Exception
     */
    public function forceDeleted(Article $article): void
    {
        $article->cover->delete();
    }
}
