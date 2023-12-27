@php
    /** @var \App\Data\Concrete\IntroDTO $intro */
    /** @var \App\Data\Concrete\AboutMeDTO $aboutMe */
    /** @var \App\Data\Concrete\ArticlesDTO $articles */
@endphp
@extends('layouts.app')

@section('body_class', 'floating-header transparent-header')

@section('content')
    @includeWhen($intro->enabled, 'partials.sections.intro')
    @includeWhen($aboutMe->enabled, 'partials.sections.about_me')
    {{--@include('partials.sections.services')--}}
    {{--@include('partials.sections.work')--}}
    {{--@include('partials.sections.skills')--}}
    @includeWhen($articles->enabled, 'partials.sections.articles')
    {{--@include('partials.sections.reviews')--}}
@endsection
