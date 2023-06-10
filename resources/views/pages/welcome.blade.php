@extends('layouts.app')

@section('body_class', 'floating-header transparent-header')

@section('content')
    @include('partials.sections.intro')
    @include('partials.sections.about_me')
    {{--@include('partials.sections.services')--}}
    {{--@include('partials.sections.work')--}}
    {{--@include('partials.sections.skills')--}}
    {{--@include('partials.sections.articles')--}}
    {{--@include('partials.sections.reviews')--}}
@endsection
