@extends('layouts.base')

@section('bodyClass', 'header-no-compensate-height header-transparent')

@section('global')

    <main>
        @include('sections.page-intro')
        @include('sections.about-me')
    </main>

@endsection
