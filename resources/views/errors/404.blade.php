<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Pagina nu a fost găsită</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-800 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-6">
        @include('partials.nav')
    </div>

    <div class="flex flex-col items-center justify-center mt-32 gap-4">
        <h1 class="text-9xl font-black text-gray-300">404</h1>
        <p class="text-2xl font-semibold text-gray-400">Pagina nu a fost găsită.</p>
    </div>
</body>
</html>
