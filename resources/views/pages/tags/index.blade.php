@extends('layouts.page')

@section('title', 'Все теги')

@section('content')
    @if($tags->isEmpty())
        <div class="alert">Нет опубликованных меток</div>
    @else
        <h1 class="page-title">Все метки</h1>
        <div class="tags tags_large">
            @foreach($tags as $tag)
                <a class="tag tag_large" href="{{ route('article.by_tag', $tag) }}">{{ $tag->name }}</a>
            @endforeach
        </div>
        <div class="mt-4">
            {{ $tags->links() }}
        </div>
    @endif
@endsection
