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

<body class="font-sans antialiased min-h-screen flex flex-col">
    @php
        $cart = session('cart', []);
        $cartQuantity   = collect($cart)->sum('quantity') ?? 0;
        $favoritesCount = count(session('favorites', []));
        $compareCount   = count(session('compare', []));
    @endphp

    <header class="bg-white shadow-md sticky top-0 z-50" x-data="{ mobileNav: false }" @keydown.window.escape="mobileNav = false">
        <div class="container mx-auto px-4 py-3 flex items-center gap-4 flex-wrap">
            <!-- Лого и Каталог -->
            <div class="flex items-center space-x-4 border border-orange p-1 rounded-lg flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center">
                    @if (file_exists(public_path('assets/header/logo.svg')))
                        <img src="{{ asset('assets/header/logo.svg') }}" alt="Логотип" class="h-10">
                    @else
                        <span class="text-2xl font-bold text-gray-800">Дом каминов</span>
                    @endif
                </a>

                <!-- Каталог с выпадающим меню (десктоп) -->
                <div x-data="{ isOpen: false }" class="relative hidden md:block">
                    <button
                        @click="isOpen = !isOpen"
                        class="bg-orange text-white px-4 py-2 rounded hover:bg-orange-white focus:outline-none flex items-center gap-1">
                        <span>Каталог</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

            <!-- Десктоп-меню -->
            <nav class="hidden md:flex space-x-6 flex-1 min-w-0">
                @include('components.nav-links')
            </nav>

            <div class="flex items-center gap-3 ml-auto">
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
                    <a href="{{ auth()->user()->is_admin ? route('admin.index') : route('profile') }}" class="hidden sm:flex items-center space-x-1">
                        <img src="{{ asset('assets/header/account-header.svg') }}" class="w-6 h-6" alt="Аккаунт">
                        <span class="text-xs text-gray-700 truncate max-w-[120px]">{{ auth()->user()->name }}</span>
                    </a>
                @else
                    <x-icon-link href="{{ route('login') }}" icon="assets/header/account-header.svg" alt="Вход" text="Вход" class="hidden sm:flex" />
                @endauth

                <!-- Бургер-меню -->
                <button @click="mobileNav = !mobileNav" class="md:hidden text-gray-700 focus:outline-none" aria-label="Открыть меню">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Мобильное меню -->
        <div
            x-show="mobileNav"
            x-cloak
            class="md:hidden border-t border-gray-200 bg-white shadow-md"
            x-transition
            @click.away="mobileNav = false">
            <div class="container mx-auto px-4 py-4 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-800">Меню</span>
                    <button @click="mobileNav = false" class="text-gray-600 hover:text-gray-800" aria-label="Закрыть меню">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-3">
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
                        <a
                            href="{{ route('catalog', ['category' => $category]) }}"
                            class="text-sm text-gray-700 hover:text-orange block">
                            {{ $key }}
                        </a>
                    @endforeach
                </div>

                <div class="flex flex-col space-y-3">
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

                <div class="flex items-center justify-between text-sm text-gray-700">
                    <a href="tel:+79179535850" class="font-semibold underline">+7 (917) 953-58-50</a>
                    <button @click="$dispatch('open-callback'); mobileNav = false" class="text-orange font-semibold">Звонок</button>
                </div>

                <div class="flex items-center justify-between text-xs text-gray-600">
                    <div class="flex items-center gap-3">
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
            </div>
        </div>
    </header>

    <!-- Модалка "Заказать звонок" -->
    <x-callback-modal />

    <main class="flex-1 shell py-10 space-y-6">
        @if (session('error'))
            <div class="surface-quiet border border-red-200 text-red-800 px-4 py-3">
                {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="surface-quiet border border-green-200 text-green-800 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-slate-900 text-white mt-10 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-orange/10 via-transparent to-amber-100/10 pointer-events-none"></div>
        <div class="shell relative py-12 space-y-10 text-slate-100">
            <div class="flex flex-col md:flex-row items-start md:items-center gap-4 justify-between bg-white/5 border border-white/10 rounded-xl p-4">
                <div>
                    <p class="text-sm text-slate-200 uppercase tracking-widest">Нужна консультация?</p>
                    <h3 class="text-xl font-semibold text-slate-50">Подберём камин под ваши задачи и бюджет</h3>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="tel:+79179535850" class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-orange text-white hover:bg-orange-white transition text-sm">
                        Позвонить
                    </a>
                    <button @click="$dispatch('open-callback')" class="inline-flex items-center gap-2 px-4 py-2 rounded-md border border-white/30 text-sm hover:border-white text-slate-50">
                        Заказать звонок
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- О компании -->
                <div class="space-y-3">
                    <h3 class="text-lg font-semibold mb-2 text-slate-50">D-Kaminov</h3>
                    <p class="text-sm text-slate-100/85 leading-relaxed">
                        Помогаем выбрать, доставить и смонтировать камины и печи. Подберём решение под интерьер и бюджет.
                    </p>
                    <div class="flex items-center gap-2 text-xs text-slate-100/80">
                        <span class="px-2 py-1 rounded bg-white/5 border border-white/10">Доставка по РФ</span>
                        <span class="px-2 py-1 rounded bg-white/5 border border-white/10">Монтаж</span>
                    </div>
                </div>

                <!-- Категории товаров -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-slate-50">Категории</h3>
                    <ul class="space-y-2 text-sm text-slate-100/90">
                        <li><a href="{{ route('catalog', ['category' => 'Биокамины']) }}" class="hover:text-orange-200 text-slate-100">Биокамины</a></li>
                        <li><a href="{{ route('catalog', ['category' => 'Газовые топки, уличные нагреватели']) }}" class="hover:text-orange-200 text-slate-100">Газовые топки, уличные нагреватели</a></li>
                        <li><a href="{{ route('catalog', ['category' => 'Электроочаги']) }}" class="hover:text-orange-200 text-slate-100">Электроочаги</a></li>
                        <li><a href="{{ route('catalog', ['category' => 'Дымоходы']) }}" class="hover:text-orange-200 text-slate-100">Дымоходы</a></li>
                        <li><a href="{{ route('catalog', ['category' => 'Печи, камины, каминокомплекты']) }}" class="hover:text-orange-200 text-slate-100">Печи, камины, каминокомплекты</a></li>
                        <li><a href="{{ route('catalog', ['category' => 'Порталы']) }}" class="hover:text-orange-200 text-slate-100">Порталы</a></li>
                        <li><a href="{{ route('catalog', ['category' => 'Топки']) }}" class="hover:text-orange-200 text-slate-100">Топки</a></li>
                        <li><a href="{{ route('catalog', ['category' => 'Каминокомплекты']) }}" class="hover:text-orange-200 text-slate-100">Каминокомплекты</a></li>
                        <li><a href="{{ route('catalog', ['category' => 'Вентиляция']) }}" class="hover:text-orange-200 text-slate-100">Вентиляция</a></li>
                        <li><a href="{{ route('catalog', ['category' => 'Каминное/печное литье']) }}" class="hover:text-orange-200 text-slate-100">Каминное/печное литье</a></li>
                    </ul>
                </div>

                <!-- Информация -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-slate-50">Информация</h3>
                    <ul class="space-y-2 text-sm text-slate-100/90">
                        <li><a href="{{ route('delivery') }}" class="hover:text-orange-200 text-slate-100">Доставка</a></li>
                        <li><a href="{{ route('montage') }}" class="hover:text-orange-200 text-slate-100">Монтаж</a></li>
                        <li><a href="{{ route('portfolio') }}" class="hover:text-orange-200 text-slate-100">Портфолио</a></li>
                        <li><a href="{{ route('contacts') }}" class="hover:text-orange-200 text-slate-100">Контакты</a></li>
                        <li><a href="{{ route('privacy.policy') }}" class="hover:text-orange-200 text-slate-100">Политика конфиденциальности</a></li>
                        <li><a href="{{ route('sitemap') }}" class="hover:text-orange-200 text-slate-100">Карта сайта</a></li>
                    </ul>
                </div>

                <!-- Контакты / Юридическая информация -->
                <div class="text-sm text-white space-y-2">
                    <h3 class="text-lg font-semibold mb-2 text-white">Контакты</h3>
                    <p class="text-white">Телефон: <a href="tel:+79179535850" class="hover:text-orange-200 text-white font-semibold">+7 (917) 953-58-50</a></p>
                    <p class="text-white">Email: <a href="mailto:info@d-kaminov.com" class="hover:text-orange-200 text-white font-semibold">info@d-kaminov.com</a></p>
                    <p class="text-white">График: Пн–Пт 10:00–19:00, Сб–Вс по записи</p>
                    <div class="pt-2">
                        <h4 class="text-xs uppercase tracking-widest text-slate-50 mb-1">Юр. данные</h4>
                        <p class="leading-relaxed text-slate-50">
                            ИП Краснянский М.А.<br>
                            ИНН 631200408027<br>
                            ОГРНИП 325632700010391
                        </p>
                    </div>
                    <div class="pt-2">
                        <p class="text-xs text-slate-100/70">Мы принимаем:</p>
                        <div class="flex gap-2 mt-1 text-xs text-slate-100/80">
                            <span class="px-2 py-1 rounded bg-white/5 border border-white/10">VISA</span>
                            <span class="px-2 py-1 rounded bg-white/5 border border-white/10">MC</span>
                            <span class="px-2 py-1 rounded bg-white/5 border border-white/10">МИР</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Блок с политикой и копирайтом -->
            <div class="pt-6 border-t border-white/10 flex flex-col sm:flex-row gap-3 sm:gap-0 sm:justify-between text-sm text-white">
                <p class="text-white">©2010-{{ now()->year }} D-Kaminov. Все права защищены.</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('privacy.policy') }}" class="hover:text-orange-200 text-white">Политика конфиденциальности</a>
                    <a href="{{ route('sitemap') }}" class="hover:text-orange-200 text-white">Карта сайта</a>
                </div>
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

    {{-- Стек для страницевых скриптов --}}
    @stack('scripts')
</body>
</html>
