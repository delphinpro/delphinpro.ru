@props(['comment'])

<?php /** @var \App\Models\Comment $comment */ ?>

<div @class(['comment-box', 'is-moderate' => !$comment->published]) id="comment-{{ $comment->id }}">
    <div class="comment-box__header">
        <div>
            <span @class([
                'comment-box__user',
                'is-admin' => $comment->user?->isAdmin(),
                'is-owner' => $comment->user_id !== null && $comment->user_id === auth()->id(),
            ])>{{ $comment->user?->name ?? 'Прохожий' }}</span>
            <time class="comment-box__date" datetime="{{ $comment->created_at }}">{{ $comment->created_at }}</time>
        </div>
        <div class="d-flex gap-1 align-items-center">
            @if(!$comment->published)
                <span class="badge is-moderate text-bg-danger">На модерации</span>
                @can('comment.moderate')
                    <button class="comment-box__btn-publish btn btn-primary btn-xs"
                        type="button"
                        data-request="{{ route('article.comments.publish', $comment) }}"
                    >Разрешить
                    </button>
                @endcan
            @endif
            @can('comment.delete', $comment)
                <button class="comment-box__btn-delete btn btn-danger btn-xs"
                    type="button"
                    data-request="{{ route('article.comments.destroy', $comment) }}"
                >Удалить
                </button>
            @endcan
        </div>
    </div>
    <div class="comment-box__body content">{!! $comment->parsed() !!}</div>
    {{-- <div class="comment-box__footer">
        <button class="btn btn-primary btn-link btn-xs">Ответить</button>
    </div> --}}
</div>
