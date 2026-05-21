<!-- resources/views/layouts/app.blade.php -->
 
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
        <title>{{ $title ?? config('app.name') }}</title>
 
        @vite(['resources/css/app.css', 'resources/js/app.js'])
 
        @livewireStyles
    </head>
    <body>
        <header style="background: linear-gradient(135deg, #1e1b4b 0%, #4f46e5 60%, #7c3aed 100%);" class="text-white p-4 flex items-center gap-3">
            <span style="  width: 36px; height: 36px; background: rgba(255, 255, 255, 0.18); border-radius: 10px;display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">📦</span>
            <h1 class="text-2xl font-bold">Plánovač výroby</h1>
        </header>
        <nav style="background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" class="px-6 flex gap-2">
            <a href="{{ route('vyrobky') }}" style="{{ request()->routeIs('vyrobky') ? 'border-bottom: 3px solid #4f46e5; color: #4f46e5;' : 'border-bottom: 3px solid transparent; color: #374151;' }}" class="no-underline font-medium py-3 px-3 hover:text-indigo-600">⚙️ Výrobky</a>
            <a href="{{ route('zakazky') }}" style="{{ request()->routeIs('zakazky') ? 'border-bottom: 3px solid #4f46e5; color: #4f46e5;' : 'border-bottom: 3px solid transparent; color: #374151;' }}" class="no-underline font-medium py-3 px-3 hover:text-indigo-600">📋 Zákazky</a>
            <a href="{{ route('prehlad') }}" style="{{ request()->routeIs('prehlad') ? 'border-bottom: 3px solid #4f46e5; color: #4f46e5;' : 'border-bottom: 3px solid transparent; color: #374151;' }}" class="no-underline font-medium py-3 px-3 hover:text-indigo-600">📊 Prehľad</a>
        </nav>
        @yield('content')
 
        @livewireScripts
    </body>
</html>