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

<body class="bg-gray-100 min-h-screen text-gray-900">

<!-- Боковое меню -->
<aside class="bg-gray-800 text-white w-64 p-6 fixed inset-y-0 left-0 z-30 hidden md:block">
    <h2 class="text-xl font-bold mb-6">Админ-панель</h2>
    <nav class="space-y-2">
        <!-- Главная -->
        <a href="{{ route('admin.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 transition-colors">Главная</a>

        <!-- Товары -->
        <a href="{{ route('admin.products') }}" class="block px-4 py-2 rounded hover:bg-gray-700 transition-colors">Товары</a>

        <!-- Категории -->
        <a href="{{ route('admin.categories') }}" class="block px-4 py-2 rounded hover:bg-gray-700 transition-colors">Категории</a>

        <!-- Заказы -->
        <a href="{{ route('admin.orders') }}" class="block px-4 py-2 rounded hover:bg-gray-700 transition-colors">Заказы</a>

        <!-- Пользователи -->
        <a href="{{ route('admin.users') }}" class="block px-4 py-2 rounded hover:bg-gray-700 transition-colors">Пользователи</a>

        <!-- Аналитика посещений -->
        <a href="{{ route('admin.analytics') }}" class="block px-4 py-2 rounded hover:bg-gray-700 transition-colors">Аналитика посещений</a>

        <!-- Настройки (опционально) -->
    </nav>
</aside>

<!-- Основной контент -->
<main class="md:ml-64 p-6">
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-6">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-4 rounded mb-6">{{ session('error') }}</div>
    @endif

    @yield('content')
</main>

<!-- Мобильное меню -->
<div x-show="open" x-cloak class="fixed inset-0 flex items-start justify-start h-full bg-black bg-opacity-50 z-20 md:hidden">
    <div class="w-64 bg-gray-800 text-white p-6 h-full shadow-lg">
        <button @click="open = false" class="absolute top-4 right-4 text-white text-2xl">×</button>
        <h2 class="text-xl font-bold mb-6">Меню</h2>
        <nav class="space-y-2">
            <a @click="open = false" href="{{ route('admin.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 transition-colors">Главная</a>
            <a @click="open = false" href="{{ route('admin.products') }}" class="block px-4 py-2 rounded hover:bg-gray-700 transition-colors">Товары</a>
            <a @click="open = false" href="{{ route('admin.categories') }}" class="block px-4 py-2 rounded hover:bg-gray-700 transition-colors">Категории</a>
            <a @click="open = false" href="{{ route('admin.orders') }}" class="block px-4 py-2 rounded hover:bg-gray-700 transition-colors">Заказы</a>
            <a @click="open = false" href="{{ route('admin.users') }}" class="block px-4 py-2 rounded hover:bg-gray-700 transition-colors">Пользователи</a>
            <a @click="open = false" href="{{ route('admin.analytics') }}" class="block px-4 py-2 rounded hover:bg-gray-700 transition-colors">Аналитика посещений</a>
        </nav>
    </div>
</div>

<!-- Кнопка мобильного меню -->
<div class="md:hidden fixed bottom-4 right-4 z-10">
    <button @click="open = !open" class="bg-orange hover:bg-blue-700 text-white p-4 rounded-full shadow-lg">☰</button>
</div>

</body>
</html>