<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

namespace App\Orchid\Controllers;

use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ArticleController extends Controller
{
    public function saveContent(Article $article, Request $request): JsonResponse
    {
        $content = $request['content'] ?? null;
        if ($content) {
            $article->update([
                'content' => $content,
            ]);
        }

        return response()->json(['ok' => true]);
    }
}
