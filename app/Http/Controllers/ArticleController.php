<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Tag;
use App\Services\Settings;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['cover', 'tags', 'comments'])
            ->lastPublished()
            ->paginate(Article::PER_PAGE);

        return view('pages.articles.index', compact('articles'));
    }

    public function show(Article $article, Settings $settings)
    {
        if (!$article->published) {
            abort(404);
        }

        $article->load('tags');

        $tagIds = $article->tags->map(fn(Tag $tag) => $tag->id)->toArray();
        $related = Article::whereHas('tags', static fn($q) => $q->whereIn('id', $tagIds))
            ->where('id', '!=', $article->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get(['id', 'title']);

        $comments = $settings->displayComments
            ? $article->comments()
                ->with(['user', 'user.roles'])
                ->when(Gate::denies('comment.moderate'), function (Builder $builder) {
                    return $builder->where(function (Builder $builder) {
                        return $builder->where('published', true)->when(Auth::id(), function (Builder $builder) {
                            return $builder->orWhere('user_id', Auth::id());
                        });
                    });
                })
                ->orderBy('created_at')
                ->get()
            : collect();

        return view('pages.articles.show', compact('article', 'related', 'comments'));
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
