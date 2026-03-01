<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', subject: app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'SIRUTA - Județe și orașe România pentru developeri') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="flex items-center  min-h-screen flex-col font-geist text-gray-700">
        <div class="sticky top-8 flex items-center gap-3 z-20">
            @include('partials.nav')
        </div>
        @yield('content')
        @include('partials.footer')
    </body>

</html>
