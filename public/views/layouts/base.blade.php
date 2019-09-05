<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@evoParser($documentObject['seoTitle'])</title>

    <link rel="stylesheet" href="/design/css/main.css">

</head>

<body class="@yield('bodyClass')">

<a class="sr-only" href="#content">Skip to content</a>

<div id="app" class="site">

    <div class="site__header">
        @include('common.header')
    </div>

    <div class="site__main" id="content">
        @section('global')
        @show
    </div>

    <div class="site__footer">
        @include('common.footer')
    </div>
</div>

<script src="/design/js/main.bundle.js"></script>

</body>
</html>
