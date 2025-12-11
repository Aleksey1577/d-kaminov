{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('seo_title', $seoData['title'])
@section('seo_description', $seoData['description'])
@section('seo_keywords', $seoData['keywords'])

@section('title', 'Главная')

@section('content')
<!-- Hero -->
<div class="section p-6 sm:p-8 md:p-10 mb-12">
    <div class="grid lg:grid-cols-2 gap-8 items-center">
        <div class="space-y-5">
            <div class="eyebrow">D-Kaminov · Самара</div>
            <h1 class="section-title">
                Камины, печи и монтаж <span class="text-orange">под ключ</span>
            </h1>
            <p class="section-lead">
                Проектируем, поставляем и устанавливаем оборудование с 2010 года.
                От уютных биокаминов до сложных дымоходных систем — всё делаем силами собственной команды.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('catalog') }}" class="btn-primary">Перейти в каталог</a>
                <button @click="$dispatch('open-callback')" class="btn-ghost">
                    Получить консультацию
                </button>
            </div>
            <div class="flex flex-wrap gap-3">
                <span class="pill">550+ реализованных проектов</span>
                <span class="pill">Гарантия 3 года</span>
                <span class="pill">Выезд в день обращения</span>
            </div>
        </div>

        @php
            $heroSlides = collect([[
                'title' => 'Доставка и монтаж',
                'subtitle' => 'Команда монтажников, согласование проекта и аккуратный монтаж за 1–3 дня.',
                'btn' => 'Выбрать решение',
                'link' => route('catalog'),
                'image' => null,
                'bg' => 'from-orange-50 via-white to-amber-100/80',
                'textColor' => 'dark',
                'overlay' => false,
            ]])->merge(
                ($slides ?? collect())->map(function ($slide) {
                    $rawImage = $slide->image_url;
                    $image = null;
                    if ($rawImage) {
                        $isAbsolute = preg_match('~^https?://~i', $rawImage);
                        $image = $isAbsolute ? $rawImage : asset(ltrim($rawImage, '/'));
                    }
                    $link = $slide->category
                        ? route('catalog', ['category' => $slide->category])
                        : route('catalog');
                    return [
                        'title' => (string) ($slide->title ?? ''),
                        'subtitle' => (string) ($slide->subtitle ?? ''),
                        'btn' => (string) ($slide->button_text ?? ''),
                        'link' => $link,
                        'image' => $image,
                        'bg' => null,
                        'textColor' => $slide->text_color ?: 'light',
                        'overlay' => false,
                    ];
                })
            );
        @endphp

        <div class="relative"
             x-data="{
                currentSlide: 0,
                slides: {{ $heroSlides->values()->toJson() }},
                timer: null,
                start() {
                    if (this.timer) clearInterval(this.timer);
                    this.timer = setInterval(() => this.currentSlide = (this.currentSlide + 1) % this.slides.length, 6000);
                },
                stop() {
                    if (this.timer) {
                        clearInterval(this.timer);
                        this.timer = null;
                    }
                },
                init() { this.start() }
             }">
            <div class="surface overflow-hidden h-96 md:h-[28rem] relative" @mouseenter="stop()" @mouseleave="start()">
                <template x-for="(slide, index) in slides" :key="index">
                    <div x-show="currentSlide === index" x-transition.opacity
                        class="absolute inset-0 flex items-center p-0 md:p-10 overflow-hidden">
                        <template x-if="slide.bg">
                            <div class="absolute inset-0" :class="['bg-gradient-to-br', slide.bg]"></div>
                        </template>
                        <template x-if="slide.image">
                            <div class="absolute inset-0 opacity-70" :style="`background-image:url(${slide.image});background-size:contain;background-position:center;background-repeat:no-repeat;`"></div>
                        </template>
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-900/50 to-transparent" x-show="slide.overlay"></div>
                        <div class="relative max-w-xl space-y-4 p-7 md:p-10" :class="slide.textColor === 'light' ? 'text-white' : 'text-slate-900'">
                            <template x-if="slide.title">
                                <h2 x-text="slide.title" class="text-3xl md:text-4xl font-bold leading-tight"></h2>
                            </template>
                            <template x-if="slide.subtitle">
                                <p x-text="slide.subtitle" class="text-lg md:text-xl opacity-90"></p>
                            </template>
                            <template x-if="slide.btn">
                                <a :href="slide.link || '{{ route('catalog') }}'"
                                   x-text="slide.btn"
                                   class="inline-flex items-center gap-2 rounded-xl px-5 py-3 font-semibold"
                                   :class="slide.textColor === 'light'
                                        ? 'border border-white/60 bg-white/10 text-white hover:bg-white/20'
                                        : 'border border-slate-900 bg-white/80 text-slate-900 hover:bg-white'">
                                </a>
                            </template>
                        </div>
                    </div>
                </template>
                <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2">
                    <template x-for="(_, index) in slides" :key="index">
                        <button
                            @click="currentSlide = index"
                            class="w-3 h-3 rounded-full border border-white/70"
                            :class="currentSlide === index ? 'bg-orange border-orange' : 'bg-white/40'">
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Категории --}}
@php
// Желаемый порядок отображения категорий
$desiredOrder = [
'Биокамины',
'Электроочаги',
'Порталы',
'Каминокомплекты',
'Топки',
'Печи-камины',
'Газовые топки, уличные нагреватели',
'Дымоходы',
'Вентиляция',
];

$categoriesCollection = collect($categories);

// Сначала категории в нужном порядке, затем любые остальные (если есть)
$sortedCategories = collect($desiredOrder)
->map(fn ($name) => $categoriesCollection->firstWhere('name', $name))
->filter()
->merge(
$categoriesCollection->reject(fn ($cat) => in_array($cat['name'], $desiredOrder, true))
);

// КАРТА СТАТИЧНЫХ КАРТИНОК по имени категории
$categoryImages = [
'Биокамины' => '/assets/category/36994.970.png',
'Электроочаги' => '/assets/category/e2lospvm8mvty3ne003sjt6ykq7m644l.jpg',
'Порталы' => '/assets/category/portal.jpg',
'Каминокомплекты' => '/assets/category/umc7hdqesslii0i4d5gh362l9vvvmz43.jpg',
'Топки' => '/assets/category/topki.png',
'Печи-камины' => '/assets/category/kamin.jpg',
'Газовые топки, уличные нагреватели' => '/assets/category/gazkamin.png',
'Дымоходы' => '/assets/category/dimohod.png',
'Вентиляция' => '/assets/category/ventilresh.png',
];
@endphp

<div class="mb-12">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <div class="eyebrow">Каталог</div>
            <h2 class="section-title">Подберите нужную категорию</h2>
            <p class="section-lead text-base">Готовые подборки с фильтрами и актуальными ценами.</p>
        </div>
        <a href="{{ route('catalog') }}" class="btn-ghost">Весь каталог</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($sortedCategories as $category)
            @php
                $imagePath = $categoryImages[$category['name']]
                    ?? $category['image_url']
                    ?? 'images/placeholder.png';
            @endphp

            <a href="/catalog?category={{ urlencode($category['name']) }}"
                class="surface overflow-hidden hover:-translate-y-1 transition-transform duration-200 flex flex-col">
                <div class="w-full h-48 bg-white flex items-center justify-center">
                    <img src="{{ asset($imagePath) }}"
                        alt="{{ $category['name'] }}"
                        class="w-full h-full object-contain mix-blend-multiply"
                        loading="lazy"
                        decoding="async">
                </div>
                <div class="p-5 text-center space-y-2">
                    <h2 class="text-xl font-semibold text-slate-900">{{ $category['name'] }}</h2>
                    <p class="text-slate-600">Посмотреть товары</p>
                </div>
            </a>
        @endforeach
    </div>
</div>


<!-- Партнеры -->
<x-partners />

<x-professional-installation />
@endsection

@push('structured-data')
<script type="application/ld+json">
    {
        "@context": "https://schema.org ",
        "@type": "WebSite",
        "name": "D-Kaminov",
        "url": "{{ route('home') }}",
        "description": "{{ $seoData['description'] }}",
        "keywords": "{{ $seoData['keywords'] }}",
        "publisher": {
            "@type": "Organization",
            "name": "D-Kaminov",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ asset('img/logo.png') }}"
            },
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "ТЦ Интермебель, Московское шоссе 16 км, 1в ст2, 2 этаж",
                "addressLocality": "Самара",
                "postalCode": "443099",
                "addressCountry": "RU"
            },
            "contactPoint": {
                "@type": "ContactPoint",
                "telephone": "+79179535850",
                "email": "info@d-kaminov.com",
                "contactType": "customer service"
            }
        }
    }
</script>
@endpush
