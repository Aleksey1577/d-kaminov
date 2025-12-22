<!DOCTYPE html>
<html lang="ru" x-data="{ open: false }" :class="{ 'overflow-hidden': open }" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Админ-панель')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>

<body class="bg-gray-50 min-h-screen text-gray-900 flex">
@php
    $navItems = [
        ['label' => 'Главная', 'route' => 'admin.index'],
        ['label' => 'Товары', 'route' => 'admin.products'],
        ['label' => 'Категории', 'route' => 'admin.categories'],
        ['label' => 'Слайды', 'route' => 'admin.slides.index'],
        ['label' => 'Портфолио', 'route' => 'admin.portfolio.index'],
        ['label' => 'Заказы', 'route' => 'admin.orders'],
        ['label' => 'Пользователи', 'route' => 'admin.users'],
        ['label' => 'Аналитика', 'route' => 'admin.analytics'],
    ];
@endphp

<aside class="bg-gray-900 text-white w-72 flex-shrink-0 hidden md:flex flex-col">
    <div class="px-6 py-6 border-b border-gray-800">
        <div class="text-lg font-bold tracking-tight">Админ-панель</div>
        <a href="{{ url('/') }}" class="text-sm text-gray-400 hover:text-white mt-1 inline-flex items-center gap-1">
            ← На сайт
        </a>
    </div>
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        @foreach ($navItems as $item)
            @php $active = request()->routeIs($item['route']); @endphp
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition
                      {{ $active ? 'bg-orange text-white' : 'text-gray-200 hover:bg-gray-800 hover:text-white' }}">
                <span class="text-sm font-medium">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>
    <div class="px-6 py-4 border-t border-gray-800 text-sm text-gray-400">
        {{ auth()->user()->name ?? 'Админ' }}
    </div>
    <a href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
       class="px-6 pb-6 text-sm text-gray-200 hover:text-white">
        Выйти
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
</aside>

<div x-show="open" x-cloak class="fixed inset-0 z-40 md:hidden">
    <div class="absolute inset-0 bg-black/50" @click="open = false"></div>
    <div class="relative bg-gray-900 text-white w-72 h-full shadow-lg">
        <div class="px-6 py-6 border-b border-gray-800 flex items-center justify-between">
            <div>
                <div class="text-lg font-bold">Админ-панель</div>
                <a href="{{ url('/') }}" class="text-sm text-gray-400 hover:text-white inline-flex items-center gap-1">
                    ← На сайт
                </a>
            </div>
            <button @click="open = false" class="text-2xl text-gray-200">&times;</button>
        </div>
        <nav class="px-3 py-4 space-y-1">
            @foreach ($navItems as $item)
                @php $active = request()->routeIs($item['route']); @endphp
                <a @click="open = false" href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition
                          {{ $active ? 'bg-orange text-white' : 'text-gray-200 hover:bg-gray-800 hover:text-white' }}">
                    <span class="text-sm font-medium">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>
</div>

<div class="flex-1 flex flex-col min-h-screen">
    <header class="bg-white border-b border-gray-200 px-4 md:px-8 py-3 flex items-center justify-between sticky top-0 z-20">
        <div class="flex items-center gap-3">
            <button @click="open = !open" class="md:hidden inline-flex items-center justify-center h-10 w-10 rounded-full border border-gray-200 text-gray-700">
                ☰
            </button>
            <div>
                <div class="text-sm text-gray-500">Админ-панель</div>
                <div class="text-lg font-semibold text-gray-900">@yield('title', 'Админ')</div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ url('/') }}" class="text-sm text-gray-600 hover:text-orange">На сайт</a>
            <span class="hidden sm:inline text-sm text-gray-400">|</span>
            <span class="hidden sm:inline text-sm text-gray-700">{{ auth()->user()->name ?? 'Админ' }}</span>
        </div>
    </header>

    <main class="p-4 md:p-8 flex-1">
        @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg mb-4 border border-green-200">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 text-red-800 px-4 py-3 rounded-lg mb-4 border border-red-200">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</div>

@yield('scripts')
</body>
</html>
