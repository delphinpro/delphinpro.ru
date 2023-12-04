@extends('layouts.app')

@section('body_class', 'floating-header transparent-header')

@section('content')
    @includeWhen($intro['enabled']??false, 'partials.sections.intro')
    @includeWhen($aboutMe['enabled']??false, 'partials.sections.about_me')
    {{--@include('partials.sections.services')--}}
    {{--@include('partials.sections.work')--}}
    {{--@include('partials.sections.skills')--}}
    @includeWhen($lastArticles['enabled']??false, 'partials.sections.articles')
    {{--@include('partials.sections.reviews')--}}
@endsection
