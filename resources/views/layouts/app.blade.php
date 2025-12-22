<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @if(isset($seo) && is_object($seo) && method_exists($seo, 'render'))

        {!! $seo->render() !!}
    @else

        <title>@yield('seo_title', 'Дом каминов — Печи, камины, биокамины, электрокамины под ключ')</title>

        <meta name="description" content="@yield('seo_description', 'Дом каминов: широкий выбор каминов, печей, аксессуаров и комплектующих. Качественные товары с доставкой и установкой.')">

        <meta name="keywords" content="@yield('seo_keywords', 'купить камин, купить биокамин, купить электрокамин, купить печь, купить печь-камин, купить топку')">

        <meta name="robots" content="@yield('seo_robots', 'index,follow')">

        <link rel="canonical" href="{{ url()->current() }}">

        <meta property="og:title" content="@yield('seo_title', 'Дом каминов — Печи, камины, биокамины, электрокамины под ключ')">
        <meta property="og:description" content="@yield('seo_description', 'Дом каминов: широкий выбор каминов, печей, аксессуаров и комплектующих. Качественные товары с доставкой и установкой.')">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:image" content="@yield('seo_image', asset('assets/placeholder.png'))">
        <meta property="og:site_name" content="Дом каминов">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="@yield('seo_title', 'Дом каминов — Печи, камины, биокамины, электрокамины под ключ')">
        <meta name="twitter:description" content="@yield('seo_description', 'Дом каминов: широкий выбор каминов, печей, аксессуаров и комплектующих. Качественные товары с доставкой и установкой.')">
        <meta name="twitter:image" content="@yield('seo_image', asset('assets/placeholder.png'))">
    @endif

    @vite(['resources/css/app.css','resources/js/app.js'])

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>[x-cloak]{ display:none !important; }</style>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="yandex-verification" content="0f294fee5b68e1a0" />

    @stack('structured-data')
</head>

<body class="font-sans antialiased min-h-screen flex flex-col">
    @php
        $cart = session('cart', []);
        $cartQuantity   = collect($cart)->sum('quantity') ?? 0;
        $favoritesCount = count(session('favorites', []));
        $compareCount   = count(session('compare', []));
    @endphp

    <x-header :cart-quantity="$cartQuantity" :favorites-count="$favoritesCount" :compare-count="$compareCount" />

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

	        @php
	            $breadcrumbItems = [];
	            if (isset($breadcrumbs) && is_array($breadcrumbs)) {
	                $breadcrumbItems = $breadcrumbs;
	            } elseif (isset($seo) && is_object($seo) && method_exists($seo, 'breadcrumbs')) {
	                $breadcrumbItems = $seo->breadcrumbs();
	            }
	        @endphp
	        <x-breadcrumbs :items="$breadcrumbItems" />

	        @yield('content')
	    </main>

    <x-footer />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const scrollPos = sessionStorage.getItem('scrollPos');
            if (scrollPos) {
                window.scrollTo(0, parseInt(scrollPos));
                sessionStorage.removeItem('scrollPos');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
