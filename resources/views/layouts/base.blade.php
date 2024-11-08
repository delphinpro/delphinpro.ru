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

    @stack('vendor_styles')
    @vite(['resources/sass/app.scss'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('og')
    @stack('head')
</head>

<body class="@yield('body_class')">
@yield('body')
<svg xmlns="http://www.w3.org/2000/svg" style="position: absolute;left: -9999px;width: 1px;height: 1px;top: 0;">
    <symbol id="i-email-at" viewBox="0 0 24 24">
        <g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16 12C16 14.2091 14.2091 16 12 16C9.79086 16 8 14.2091 8 12C8 9.79086 9.79086 8 12 8C14.2091 8 16 9.79086 16 12ZM16 12V13.5C16 14.8807 17.1193 16 18.5 16V16C19.8807 16 21 14.8807 21 13.5V12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21H16"/>
        </g>
    </symbol>
    <symbol id="i-envelope" viewBox="0 0 24 24">
        <g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 7.00005L10.2 11.65C11.2667 12.45 12.7333 12.45 13.8 11.65L20 7"/>
            <rect x="3" y="5" width="18" height="14" rx="2"/>
        </g>
    </symbol>
    <symbol id="i-telegram" viewBox="0 0 24 24">
        <g fill="currentColor" fill-rule="evenodd" clip-rule="evenodd">
            <path d="M23.1117 4.49449C23.4296 2.94472 21.9074 1.65683 20.4317 2.227L2.3425 9.21601C0.694517 9.85273 0.621087 12.1572 2.22518 12.8975L6.1645 14.7157L8.03849 21.2746C8.13583 21.6153 8.40618 21.8791 8.74917 21.968C9.09216 22.0568 9.45658 21.9576 9.70712 21.707L12.5938 18.8203L16.6375 21.8531C17.8113 22.7334 19.5019 22.0922 19.7967 20.6549L23.1117 4.49449ZM3.0633 11.0816L21.1525 4.0926L17.8375 20.2531L13.1 16.6999C12.7019 16.4013 12.1448 16.4409 11.7929 16.7928L10.5565 18.0292L10.928 15.9861L18.2071 8.70703C18.5614 8.35278 18.5988 7.79106 18.2947 7.39293C17.9906 6.99479 17.4389 6.88312 17.0039 7.13168L6.95124 12.876L3.0633 11.0816ZM8.17695 14.4791L8.78333 16.6015L9.01614 15.321C9.05253 15.1209 9.14908 14.9366 9.29291 14.7928L11.5128 12.573L8.17695 14.4791Z"/>
        </g>
    </symbol>
</svg>
@stack('vendor_scripts')
@vite(['resources/js/app.js'])
@stack('user_scripts')

@if($settings->enableAnalytics)
    {!! $settings->analyticsCode !!}
@endif
</body>
</html>
