@props([
    'cartQuantity' => 0,
    'favoritesCount' => 0,
    'compareCount' => 0,
])

@php
    $catalogLinks = [
        'Биокамины' => 'Биокамины',
        'Газовые топки' => 'Газовые топки, уличные нагреватели',
        'Электроочаги' => 'Электроочаги',
        'Дымоходы' => 'Дымоходы',
        'Печи и камины' => 'Печи, камины, каминокомплекты',
        'Порталы' => 'Порталы',
        'Топки' => 'Топки',
        'Каминокомплекты' => 'Каминокомплекты',
        'Вентиляция' => 'Вентиляция',
        'Каминное литье' => 'Каминное/печное литье',
    ];
    $user = auth()->user();
    $isAdmin = $user?->is_admin;
    $profileUrl = $user ? ($isAdmin ? route('admin.index') : route('profile')) : null;
@endphp

<header class="bg-white shadow-md sticky top-0 z-50" x-data="{ mobileNav: false }" @keydown.window.escape="mobileNav = false">
    <div class="container mx-auto px-4 py-3 flex items-center gap-4 flex-wrap">
        <div class="flex items-center space-x-4 flex-shrink-0 md:border md:border-orange md:p-1 md:rounded-lg">
            <a href="{{ route('home') }}" class="flex items-center">
                @if (file_exists(public_path('assets/header/logo.svg')))
                    <img src="{{ asset('assets/header/logo.svg') }}" alt="Логотип" class="h-10">
                @else
                    <span class="text-2xl font-bold text-gray-800">Дом каминов</span>
                @endif
            </a>

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
                        @foreach ($catalogLinks as $key => $category)
                            <li>
                                <a href="{{ route('catalog', ['category' => \Illuminate\Support\Str::slug($category)]) }}"
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

        <nav class="hidden md:flex space-x-6 flex-1 min-w-0">
            @include('components.nav-links')
        </nav>

        <div class="flex items-center gap-3 ml-auto">
            <div class="hidden md:inline-flex items-center space-x-4">
                <a href="tel:+79179535850" class="text-gray-700 font-semibold underline">
                    +7 (917) 953-58-50
                </a>
                <button @click="$dispatch('open-callback')" class="px-4 py-2 hover:underline">
                    Заказать звонок
                </button>
            </div>

            <div class="inline-flex items-center gap-3 md:gap-4 relative">
                <x-icon-search />
                <div class="hidden md:flex items-center gap-4">
                    <x-icon-link href="{{ route('compare') }}" icon="assets/header/sravnenie.svg" alt="Сравнение" text="Сравнение" :badge="$compareCount" />
                    <x-icon-link href="{{ route('favorites') }}" icon="assets/header/favourite-header.svg" alt="Избранное" text="Избранное" :badge="$favoritesCount" />
                </div>
                <x-icon-link href="{{ route('cart') }}" icon="assets/header/shopping-cart.svg" alt="Корзина" text="Корзина" :badge="$cartQuantity" />
            </div>

            @auth
                <a href="{{ $profileUrl }}" class="hidden sm:flex items-center space-x-1">
                    <img src="{{ asset('assets/header/account-header.svg') }}" class="w-6 h-6" alt="Аккаунт">
                    <span class="text-xs text-gray-700 truncate max-w-[120px]">{{ auth()->user()->name }}</span>
                </a>
            @else
                <x-icon-link href="{{ route('login') }}" icon="assets/header/account-header.svg" alt="Вход" text="Вход" class="hidden sm:flex" />
            @endauth

            <button
                type="button"
                @click="mobileNav = !mobileNav"
                class="md:hidden text-gray-700 focus:outline-none p-1"
                aria-label="Открыть меню"
                :aria-expanded="mobileNav">
                <span class="relative flex flex-col justify-center w-7 h-7">
                    <span class="h-0.5 w-7 bg-current rounded-full transform transition-all duration-300 ease-in-out origin-center" :class="{ 'translate-y-2 rotate-45': mobileNav }"></span>
                    <span class="h-0.5 w-7 bg-current rounded-full my-1.5 transform transition-all duration-300 ease-in-out origin-center" :class="{ 'opacity-0': mobileNav }"></span>
                    <span class="h-0.5 w-7 bg-current rounded-full transform transition-all duration-300 ease-in-out origin-center" :class="{ '-translate-y-2 -rotate-45': mobileNav }"></span>
                </span>
            </button>
        </div>
    </div>

    <div
        x-show="mobileNav"
        x-cloak
        class="md:hidden border-t border-gray-200 bg-white shadow-md origin-top transform"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
        @click.away="mobileNav = false">
        <div class="container mx-auto px-4 py-4 space-y-5" x-data="{ catalogOpen: false }">
            <div class="flex items-center justify-between">
                <button
                    type="button"
                    @click="catalogOpen = !catalogOpen"
                    class="flex items-center gap-2 px-3 py-2 rounded-xl text-slate-800 hover:text-orange hover:bg-amber-50 transition font-semibold">
                    <span>Категория</span>
                    <svg class="w-4 h-4 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" :class="{ 'rotate-180': catalogOpen }">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <button @click="mobileNav = false" class="text-gray-600 hover:text-gray-800" aria-label="Закрыть меню">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div
                x-show="catalogOpen"
                x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-1 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 -translate-y-1 scale-95"
                class="rounded-lg border border-gray-200 bg-gray-50 p-3 origin-top transform">
                <div class="grid grid-cols-2 gap-3">
                    @foreach ($catalogLinks as $key => $category)
                        <a
                            href="{{ route('catalog', ['category' => \Illuminate\Support\Str::slug($category)]) }}"
                            class="text-sm text-gray-700 hover:text-orange block">
                            {{ $key }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-col space-y-2 border-t border-gray-200 pt-4">
                @include('components.nav-links')
                @auth
                    @if ($isAdmin)
                        <a href="{{ route('admin.index') }}" class="text-gray-700 hover:text-orange">Админ-панель</a>
                    @endif
                    <a href="{{ $profileUrl }}" class="text-gray-700 hover:text-orange">Личный кабинет</a>
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
                    <a href="{{ $profileUrl }}" class="flex items-center space-x-1">
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
