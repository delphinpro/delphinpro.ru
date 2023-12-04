@extends('layouts.base')

@section('body')
    <a class="visually-hidden" href="#content">Skip to content</a>

    <div id="app" class="site">

        <div class="site__header">
            @include('partials.app.header')
        </div>

        <div class="site__main" id="content">
            <div class="container">
                <div class="page">
                    <div class="row">
                        <div class="col-lg-12">
                            @yield('content')
                        </div>
                        {{--
                        <div class="col-lg-4">
                        </div>
                        --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="site__footer">
            @include('partials.app.footer')
        </div>
    </div>
@endsection
