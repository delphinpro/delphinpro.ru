@extends('layouts.base')

@section('body')
    <a class="visually-hidden" href="#content">Skip to content</a>

    <div id="app" class="site">

        <div class="site__header">
            @include('partials.app.header')
        </div>

        <div class="site__main" id="content">
            @yield('content')
        </div>

        <div class="site__footer">
            @include('partials.app.footer')
        </div>
    </div>
@endsection
