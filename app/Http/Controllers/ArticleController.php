<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Tag;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('tags')->lastPublished()->paginate(Article::PER_PAGE);

        return view('pages.article_index', compact('articles'));
    }

    public function show(Article $article)
    {
        if (!$article->published) {
            abort(404);
        }

        $article->load('tags');

        return view('pages.article_show', compact('article'));
    }

    public function tags()
    {
        return view('pages.tags.index', [
            'tags' => Tag::orderBy('name')->paginate(300),
        ]);
    }

    public function byTag(Tag $tag)
    {
        $articles = Article::whereHas('tags', static fn($q) => $q->where('id', $tag->id))
            ->with('tags')
            ->lastPublished()
            ->paginate(Article::PER_PAGE);

        return view('pages.tags.show', compact('articles', 'tag'));
    }
}
