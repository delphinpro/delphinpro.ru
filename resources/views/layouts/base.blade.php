<!DOCTYPE html>
@php /** @var \App\Services\Settings $settings */ @endphp
@inject('settings',App\Services\Settings::class)
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @hasSection('og')prefix="og: http://ogp.me/ns#"@endif>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @hasSection('title')
        <title>@yield('title') :: {{ config('app.name') }}</title>
    @else
        <title>{{ config('app.name') }}</title>
    @endif

    @yield('meta')

    <link rel="shortcut icon" href="{{ url(asset('favicon.ico')) }}">

    @yield('vendor_styles')
    @vite(['resources/sass/app.scss'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('og')
    @yield('head')
</head>

<body class="@yield('body_class')">
@yield('body')
@yield('vendor_scripts')
@vite(['resources/js/app.js'])

@if($settings->enableAnalytics)
    {!! $settings->analyticsCode !!}
@endif
</body>
</html>
