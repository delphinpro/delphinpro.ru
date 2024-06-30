<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2024.
 */

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Throwable;

class CommentController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function store(Request $request, Article $article): JsonResponse
    {
        $input = $request->validate([
            'content' => 'required|string|min:5',
        ], [
            'required' => 'Комментарий пуст',
            'min'      => 'Вряд ли вы смогли выразить свою мысль в столь коротком предложении.',
        ]);

        /** @var Comment $comment */
        $comment = $article->comments()->make($input)->fill(['user_id' => auth()->id()]);
        $comment->save();

        return response()->json([
            'message' => 'Комментарий будет опубликован после модерации',
            'content' => Blade::render('<x-comment-box :comment="$comment" />', compact('comment')),
        ]);
    }

    public function destroy(Comment $comment): JsonResponse
    {
        if (Gate::denies('comment.delete', $comment)) {
            abort(403, 'Вы не можете удалить этот комментарий');
        }

        $comment->delete();

        return response()->json(['message' => 'Комментарий удалён']);
    }

    public function moderate(Comment $comment): JsonResponse
    {
        if (Gate::denies('comment.moderate', $comment)) {
            abort(403, 'Недостаточно прав');
        }

        try {

            $comment->update(['published' => true]);

            return response()->json(['message' => 'Публикация комментария одобрена']);

        } catch (Throwable $e) {

            abort(500, app()->isLocal() ? $e->getMessage() : '');

        }
    }

    public function preview(Request $request): JsonResponse
    {
        $input = $request->validate([
            'content' => 'required|string|min:5',
        ], [
            'required' => 'Комментарий пуст',
            'min'      => 'Вряд ли вы смогли выразить свою мысль в столь коротком предложении.',
        ]);

        $html = Comment::make($input)->parsed();

        return response()->json([
            'content' => $html,
        ]);
    }
}
