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
        <header class="text-white p-4 flex items-center gap-3" style="background: linear-gradient(135deg, #1e1b4b 0%, #4f46e5 60%, #7c3aed 100%);">
            <span class="w-9 h-9 bg-white/20 rounded-[10px] flex items-center justify-center text-lg shrink-0">📦</span>
            <h1 class="text-2xl font-bold">Plánovač výroby</h1>
            <div class="ml-auto flex items-center gap-3">
                <span class="text-white/80 text-sm">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-white/20 hover:bg-white/30 text-white text-sm px-3 py-1.5 rounded-lg transition">
                        Odhlásiť sa
                    </button>
                </form>
            </div>
        </header>

        <nav class="bg-white shadow-[0_2px_4px_rgba(0,0,0,0.1)] px-6 flex gap-2">
            <a href="{{ route('vyrobky') }}" class="nav-link {{ request()->routeIs('vyrobky') ? 'active' : '' }}">⚙️ Výrobky</a>
            <a href="{{ route('zakazky') }}" class="nav-link {{ request()->routeIs('zakazky') ? 'active' : '' }}">📋 Zákazky</a>
            <a href="{{ route('prehlad') }}" class="nav-link {{ request()->routeIs('prehlad') ? 'active' : '' }}">📊 Prehľad</a>
        </nav>

        @yield('content')

        @livewireScripts
    </body>
</html>
