@extends('layouts.app')

@section('title', '404. Страница не найдена')

@section('content')
    <div class="container page404">
        <div class="page404__code">
            <span>
                <span class="page404__dot">&lt;</span>404<span class="page404__dot">/&gt;</span>
            </span>
        </div>
        <div class="page404__text">
            Страница не найдена
        </div>
        <div class="page404__desc">
            Эта страница была удалена, либо никогда не существовала
        </div>
    </div>
@endsection
