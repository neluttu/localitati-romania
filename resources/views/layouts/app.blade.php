<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="api-token" content="{{ config('services.homepage.api_token') }}">
    <meta name="api-url" content="{{ config('services.homepage.api_url') }}">
    <title>{{ config('app.name', 'SIRUTA - Județe și orașe România pentru developeri') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col font-geist text-gray-700">
    {{-- Top Navigation --}}
    <div class="sticky top-4 flex items-center justify-center z-20 px-4">
        @include('partials.nav')
    </div>

    {{-- Main Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    @include('partials.footer')
</body>

</html>
