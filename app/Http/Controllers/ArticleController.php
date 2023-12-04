<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Http\Controllers;

use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::lastPublished()->paginate(6);

        return view('pages.article_index', compact('articles'));
    }

    public function show(Article $article)
    {
        if (!$article->published) {
            abort(404);
        }

        return view('pages.article_show', compact('article'));
    }
}
