<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plánovač</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">Plánovač</h1>
        <p class="text-gray-500 mb-8">Prihlás sa alebo si vytvor účet</p>

        <div class="flex gap-4 justify-center">
            <a href="{{ route('login') }}"
               class="px-6 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition font-medium">
                Prihlásiť sa
            </a>
            <a href="{{ route('register') }}"
               class="px-6 py-3 bg-white text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium">
                Registrovať
            </a>
        </div>
    </div>
</body>
</html>
