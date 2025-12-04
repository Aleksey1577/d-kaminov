<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @if(isset($seo) && is_object($seo) && method_exists($seo, 'render'))
        {{-- Новый мощный SEO-рендер из SeoService --}}
        {!! $seo->render() !!}
    @else
        {{-- Фоллбэк на старую схему через @yield --}}
        <!-- SEO Title -->
        <title>@yield('seo_title', 'D-Kaminov — Печи, камины, биокамины, электрокамины под ключ')</title>

        <!-- SEO Description -->
        <meta name="description" content="@yield('seo_description', 'D-Kaminov: широкий выбор каминов, печей, аксессуаров и комплектующих. Качественные товары с доставкой и установкой.')">

        <!-- SEO Keywords -->
        <meta name="keywords" content="@yield('seo_keywords', 'купить камин, купить биокамин, купить электрокамин, купить печь, купить печь-камин, купить топку')">

        <!-- Robots -->
        <meta name="robots" content="@yield('seo_robots', 'index,follow')">

        <!-- Canonical URL -->
        <link rel="canonical" href="{{ url()->current() }}">

        <!-- Open Graph -->
        <meta property="og:title" content="@yield('seo_title', 'D-Kaminov — Печи, камины, биокамины, электрокамины под ключ')">
        <meta property="og:description" content="@yield('seo_description', 'D-Kaminov: широкий выбор каминов, печей, аксессуаров и комплектующих. Качественные товары с доставкой и установкой.')">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:image" content="@yield('seo_image', asset('img/logo.png'))">
        <meta property="og:site_name" content="D-Kaminov">

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="@yield('seo_title', 'D-Kaminov — Печи, камины, биокамины, электрокамины под ключ')">
        <meta name="twitter:description" content="@yield('seo_description', 'D-Kaminov: широкий выбор каминов, печей, аксессуаров и комплектующих. Качественные товары с доставкой и установкой.')">
        <meta name="twitter:image" content="@yield('seo_image', asset('img/logo.png'))">
    @endif

    {{-- Стили/скрипты проекта (одним вызовом) --}}
    @vite(['resources/css/app.css','resources/js/app.js'])

    {{-- Alpine.js --}}
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>[x-cloak]{ display:none !important; }</style>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="yandex-verification" content="0f294fee5b68e1a0" />

    {{-- Структурированные данные (если где-то делается @push('structured-data')) --}}
    @stack('structured-data')
</head>

<body class="font-sans antialiased bg-gray-100 min-h-screen flex flex-col" x-data="{ isOpen: false }">
    @php
        $cart = session('cart', []);
        $cartQuantity   = collect($cart)->sum('quantity') ?? 0;
        $favoritesCount = count(session('favorites', []));
        $compareCount   = count(session('compare', []));
    @endphp

    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <!-- Лого -->
            <div class="inline-flex items-center space-x-4 border border-orange p-1 rounded-lg">
                <a href="{{ route('home') }}">
                    @if (file_exists(public_path('assets/header/logo.svg')))
                        <img src="{{ asset('assets/header/logo.svg') }}" alt="Логотип" class="h-10">
                    @else
                        <span class="text-2xl font-bold text-gray-800">Дом каминов</span>
                    @endif
                </a>

                <!-- Каталог с выпадающим меню -->
                <div x-data="{ isOpen: false }" class="relative">
                    <button
                        @click="isOpen = !isOpen"
                        class="bg-orange text-white px-4 py-2 rounded hover:bg-orange-white hidden md:block focus:outline-none">
                        Каталог
                        <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div
                        x-show="isOpen"
                        x-cloak
                        class="absolute left-0 mt-2 w-64 bg-white shadow-lg rounded-md py-2 z-50"
                        @click.away="isOpen = false"
                        x-transition>
                        <ul class="space-y-2 px-4">
                            @foreach ([
                                'Биокамины' => 'Биокамины',
                                'Газовые топки' => 'Газовые топки, уличные нагреватели',
                                'Электроочаги' => 'Электроочаги',
                                'Дымоходы' => 'Дымоходы',
                                'Печи и камины' => 'Печи, камины, каминокомплекты',
                                'Порталы' => 'Порталы',
                                'Топки' => 'Топки',
                                'Каминокомплекты' => 'Каминокомплекты',
                                'Вентиляция' => 'Вентиляция',
                                'Каминное литье' => 'Каминное/печное литье'
                            ] as $key => $category)
                                <li>
                                    <a href="{{ route('catalog', ['category' => $category]) }}"
                                       class="block py-1 text-gray-700 hover:text-orange"
                                       @click="isOpen = false">
                                        {{ $key }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Бургер-меню -->
            <div x-data="{ isOpen: false }" class="md:hidden">
                <button @click="isOpen = !isOpen" class="text-gray-700 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <!-- Десктоп-меню -->
            <nav class="hidden md:inline-flex space-x-6">
                @include('components.nav-links')
            </nav>

            <!-- Мобильное меню -->
            <div x-show="isOpen" x-cloak class="absolute top-16 left-0 w-full bg-white shadow-md md:hidden"
                 @click.away="isOpen = false" x-transition>
                <div class="flex flex-col p-4 space-y-4">
                    @include('components.nav-links')
                    @auth
                        @if (auth()->user()->is_admin)
                            <a href="{{ route('admin.index') }}" class="text-gray-700 hover:text-orange">Админ-панель</a>
                        @endif
                        <a href="{{ auth()->user()->is_admin ? route('admin.index') : route('profile') }}"
                           class="text-gray-700 hover:text-orange">Личный кабинет</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-orange">Войти</a>
                        <a href="{{ route('register') }}" class="text-gray-700 hover:text-orange">Регистрация</a>
                    @endauth
                </div>
            </div>

            <!-- Контакты и Заказать звонок -->
            <div class="hidden md:inline-flex items-center space-x-4">
                <a href="tel:+79179535850" class="text-gray-700 font-semibold underline">
                    +7 (917) 953-58-50
                </a>
                <button @click="$dispatch('open-callback')" class="px-4 py-2 hover:underline">
                    Заказать звонок
                </button>
            </div>

            <!-- Иконки -->
            <div class="inline-flex space-x-4 relative">
                <x-icon-search />
                <x-icon-link href="{{ route('compare') }}" icon="assets/header/sravnenie.svg" alt="Сравнение" text="Сравнение" :badge="$compareCount" />
                <x-icon-link href="{{ route('favorites') }}" icon="assets/header/favourite-header.svg" alt="Избранное" text="Избранное" :badge="$favoritesCount" />
                <x-icon-link href="{{ route('cart') }}" icon="assets/header/shopping-cart.svg" alt="Корзина" text="Корзина" :badge="$cartQuantity" />
            </div>

            @auth
                <a href="{{ auth()->user()->is_admin ? route('admin.index') : route('profile') }}" class="flex items-center space-x-1">
                    <img src="{{ asset('assets/header/account-header.svg') }}" class="w-6 h-6" alt="Аккаунт">
                    <span class="text-xs text-gray-700">{{ auth()->user()->name }}</span>
                </a>
            @else
                <x-icon-link href="{{ route('login') }}" icon="assets/header/account-header.svg" alt="Вход" text="Вход" />
            @endauth
        </div>
    </header>

    <!-- Модалка "Заказать звонок" -->
    <x-callback-modal />

    <main class="container mx-auto px-4 py-8 flex-grow">
        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6">{{ session('success') }}</div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Категории товаров -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Категории</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('catalog', ['category' => 'Биокамины']) }}" class="hover:text-orange">Биокамины</a></li>
                        <li><a href="{{ route('catalog', ['category' => 'Газовые топки, уличные нагреватели']) }}" class="hover:text-orange">Газовые топки, уличные нагреватели</a></li>
                        <li><a href="{{ route('catalog', ['category' => 'Электроочаги']) }}" class="hover:text-orange">Электроочаги</a></li>
                        <li><a href="{{ route('catalog', ['category' => 'Дымоходы']) }}" class="hover:text-orange">Дымоходы</a></li>
                        <li><a href="{{ route('catalog', ['category' => 'Печи, камины, каминокомплекты']) }}" class="hover:text-orange">Печи, камины, каминокомплекты</a></li>
                        <li><a href="{{ route('catalog', ['category' => 'Порталы']) }}" class="hover:text-orange">Порталы</a></li>
                        <li><a href="{{ route('catalog', ['category' => 'Топки']) }}" class="hover:text-orange">Топки</a></li>
                        <li><a href="{{ route('catalog', ['category' => 'Каминокомплекты']) }}" class="hover:text-orange">Каминокомплекты</a></li>
                        <li><a href="{{ route('catalog', ['category' => 'Вентиляция']) }}" class="hover:text-orange">Вентиляция</a></li>
                        <li><a href="{{ route('catalog', ['category' => 'Каминное/печное литье']) }}" class="hover:text-orange">Каминное/печное литье</a></li>
                    </ul>
                </div>

                <!-- Информация -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Информация</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('delivery') }}" class="hover:text-orange">Доставка</a></li>
                        <li><a href="#" class="hover:text-orange">Оплата</a></li>
                        <li><a href="#" class="hover:text-orange">Гарантия</a></li>
                        <li><a href="{{ route('contacts') }}" class="hover:text-orange">Контакты</a></li>
                    </ul>
                </div>

                <!-- Контакты -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Контакты</h3>
                    <p>Телефон: <a href="tel:+79179535850" class="hover:text-orange">+7 (917) 953-58-50</a></p>
                    <p>Email: <a href="mailto:info@d-kaminov.com" class="hover:text-orange">info@d-kaminov.com</a></p>
                </div>

                <!-- Юридические данные -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Юридические данные</h3>
                    <p class="text-sm">
                        ИП Краснянский А.М.<br>
                        ИНН 631200408027<br>
                        ОГРНИП 325632700010391
                    </p>
                </div>
            </div>

            <!-- Блок с политикой и копирайтом -->
            <div class="mt-8 pt-6 border-t border-gray-700 flex justify-between text-sm">
                <p>©2010-2026 D-Kaminov. Все права защищены.</p>
                <p>
                    <a href="{{ route('privacy.policy') }}" class="hover:text-orange">
                        Политика конфиденциальности
                    </a>
                </p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const scrollPos = sessionStorage.getItem('scrollPos');
            if (scrollPos) {
                window.scrollTo(0, parseInt(scrollPos));
                sessionStorage.removeItem('scrollPos');
            }
        });
    </script>
</body>
</html>
