@props(['article'])

<?php /** @var \App\Models\Article $article */ ?>

<form class="comment-form"
    method="post" action="{{ route('article.comments.store', $article) }}"
    data-preview-action="{{ route('article.comments.preview') }}"
    data-target-id="comments"
>
    @csrf
    <fieldset>
        <div class="comment-form__editor show">
            <p>Поддерживается разметка markdown</p>
            <textarea name="content" class="ta-comment form-control" rows="10"></textarea>
        </div>
        <div class="comment-form__preview content"></div>
        <div class="comment-form__actions">
            <button type="submit" class="comment-form__btn-send btn btn-primary">Отправить</button>
            <button type="button" class="comment-form__btn-preview btn btn-light">
                Предварительный просмотр
            </button>
        </div>
        <div class="alert alert-primary">
            Комментарий будет опубликован после модерации
        </div>
    </fieldset>
</form>
