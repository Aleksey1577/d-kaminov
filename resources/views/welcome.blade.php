{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('seo_title', $seoData['title'])
@section('seo_description', $seoData['description'])
@section('seo_keywords', $seoData['keywords'])

@section('title', 'Главная')

@section('content')
<!-- Hero блок -->
<div class="mb-16">
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Слайдер (70%) -->
        <div class="w-full md:w-7/10"
            x-data="{
                    currentSlide: 0,
                    slides: [
                        { title: 'Новая коллекция', subtitle: 'Откройте последние тенденции', btn: 'Смотреть', link: '/new', bg: 'bg-blue-50' },
                        { title: 'Скидки до 50%', subtitle: 'Специальные предложения', btn: 'Акции', link: '/sales', bg: 'bg-amber-50' }
                    ],
                    init() { setInterval(() => this.currentSlide = (this.currentSlide + 1) % this.slides.length, 5000) }
                }">
            <div class="relative h-80 md:h-96 rounded-xl overflow-hidden">
                <template x-for="(slide, index) in slides" :key="index">
                    <div x-show="currentSlide === index" x-transition.opacity
                        class="absolute inset-0 flex items-center p-8 md:p-12"
                        :class="slide.bg">
                        <div class="max-w-lg">
                            <h2 x-text="slide.title" class="text-3xl md:text-4xl font-bold mb-4"></h2>
                            <p x-text="slide.subtitle" class="text-lg mb-6"></p>
                            <a :href="slide.link"
                                x-text="slide.btn"
                                class="inline-block px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition"></a>
                        </div>
                    </div>
                </template>
                <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2">
                    <template x-for="(_, index) in slides" :key="index">
                        <button
                            @click="currentSlide = index"
                            class="w-3 h-3 rounded-full"
                            :class="currentSlide === index ? 'bg-black' : 'bg-gray-300'">
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Текстовый блок (30%) -->
        <div class="w-full md:w-3/10 bg-gray-100 rounded-xl p-8 flex flex-col justify-center">
            <h2 class="text-2xl md:text-3xl font-bold mb-4">Наши преимущества</h2>
            <p class="text-gray-700 mb-6">Бесплатная доставка от 5000₽. Гарантия качества.</p>
            <a href="/advantages"
                class="inline-block px-6 py-3 border-2 border-black rounded-lg hover:bg-black hover:text-white transition">
                Подробнее
            </a>
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

<div class="mb-12 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @foreach ($sortedCategories as $category)
    @php

    $imagePath = $categoryImages[$category['name']]
    ?? $category['image_url']
    ?? 'images/placeholder.png';
    @endphp

    <a href="/catalog?category={{ urlencode($category['name']) }}"
        class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
        <img src="{{ asset($imagePath) }}"
            alt="{{ $category['name'] }}"
            class="w-full h-48 object-contain">
        <div class="p-4 text-center">
            <h2 class="text-xl font-semibold text-gray-800">{{ $category['name'] }}</h2>
            <p class="text-gray-600 mt-2">Посмотреть товары</p>
        </div>
    </a>
    @endforeach
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