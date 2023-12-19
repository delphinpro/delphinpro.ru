@extends('layouts.base')

@section('body')
    <a class="visually-hidden" href="#content">Skip to content</a>

    <div id="app" class="site">

        <div class="site__header">
            @include('partials.app.header')
        </div>

        <div class="site__main" id="content">
            <div class="container">
                <div class="page @hasSection('aside') page_columns @endif">
                    <main class="page__main" role="main">
                        @yield('content')
                    </main>
                    @hasSection('aside')
                        <aside class="page__side shadow-sm">
                            @yield('aside')
                        </aside>
                    @endif
                </div>
            </div>
        </div>

        <div class="site__footer">
            @include('partials.app.footer')
        </div>
    </div>
@endsection
