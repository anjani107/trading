<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Trading Journal — @yield('title', 'Dashboard')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen">

    <nav class="bg-slate-900 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <span class="text-2xl">📈</span>
                        <span class="font-bold text-lg">Trading Journal</span>
                    </a>
                    <div class="hidden md:flex space-x-1">
                        <a href="{{ route('dashboard') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-slate-700' : 'hover:bg-slate-800' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('trades.index') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('trades.index') ? 'bg-slate-700' : 'hover:bg-slate-800' }}">
                            All Trades
                        </a>
                        <a href="{{ route('trades.create') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('trades.create') ? 'bg-slate-700' : 'hover:bg-slate-800' }}">
                            New Trade
                        </a>
                    </div>
                </div>
                <a href="{{ route('trades.create') }}"
                   class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-md text-sm font-semibold transition">
                    + Log Trade
                </a>
            </div>
        </div>
    </nav>

    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-800 px-4 py-3 rounded-md">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <footer class="text-center text-sm text-slate-500 py-6">
        Trading Journal · Built with Laravel
    </footer>
</body>
</html>
