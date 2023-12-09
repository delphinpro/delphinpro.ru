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
    @vite(['resources/sass/app.scss'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('og')
    @yield('head')
</head>

<body class="@yield('body_class')">
@yield('body')
@vite(['resources/js/app.js'])

{{-- <!-- @formatter:off Yandex.Metrika counter --> <script type="text/javascript" > (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)}; m[i].l=1*new Date(); for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }} k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)}) (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym"); ym(32286694, "init", { clickmap:true, trackLinks:true, accurateTrackBounce:true }); </script> <noscript><div><img src="https://mc.yandex.ru/watch/32286694" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter @formatter:on --> --}}
@if($settings->enableAnalytics)
{!! $settings->analyticsCode !!}
@endif
</body>
</html>
