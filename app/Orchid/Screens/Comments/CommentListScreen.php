<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2024.
 */

namespace App\Orchid\Screens\Comments;

use App\Models\Comment;
use App\Orchid\Helpers\Display;
use App\Services\Settings;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class CommentListScreen extends Screen
{
    public function query(Settings $settings): iterable
    {
        $comments = Comment::orderByDesc('created_at')
            ->paginate($settings->adminPaginationCount);

        return [
            'comments' => $comments,
        ];
    }

    public function name(): ?string
    {
        return 'Последние комментарии';
    }

    public function commandBar(): iterable
    {
        return [];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('comments', [
                TD::make('id'),
                TD::make('Опубликован')->render(function (Comment $comment) {
                    return Display::bool($comment->published);
                }),
                TD::make('Объект')->render(function (Comment $comment) {
                    return '<a href="'.route('article.show',
                            $comment->commentable->id).'#comment-'.$comment->id.'" target="_blank">'.$comment->commentable->title.'</a>';
                }),
                TD::make('created_at', 'Время')->render(function (Comment $comment) {
                    return Display::datetime($comment->created_at->timezone(getUserTimeZone()));
                }),
            ]),
        ];
    }
}
