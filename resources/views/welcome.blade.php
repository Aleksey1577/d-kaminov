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
        <div class="w-full md:w-7/10" x-data="{
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
                        class="absolute inset-0 flex items-center p-8 md:p-12" :class="slide . bg">
                        <div class="max-w-lg">
                            <h2 x-text="slide.title" class="text-3xl md:text-4xl font-bold mb-4"></h2>
                            <p x-text="slide.subtitle" class="text-lg mb-6"></p>
                            <a :href="slide . link" x-text="slide.btn"
                                class="inline-block px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition"></a>
                        </div>
                    </div>
                </template>
                <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2">
                    <template x-for="(_, index) in slides" :key="index">
                        <button @click="currentSlide = index" class="w-3 h-3 rounded-full" :class="currentSlide === index ? 'bg-black' : 'bg-gray-300'"></button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Текстовый блок (30%) -->
        <div class="w-full md:w-3/10 bg-gray-100 rounded-xl p-8 flex flex-col justify-center">
            <h2 class="text-2xl md:text-3xl font-bold mb-4">Наши преимущества</h2>
            <p class="text-gray-700 mb-6">Бесплатная доставка от 5000₽. Гарантия качества.</p>
            <a href="/advantages"
                class="inline-block px-6 py-3 border-2 border-black rounded-lg hover:bg-black hover:text-white transition">Подробнее</a>
        </div>
    </div>
</div>

<!-- Категории -->
<div class="mb-12 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @foreach ($categories as $category)
    <a href="/catalog?category={{ urlencode($category['name']) }}"
        class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
        <img src="{{ $category['image_url'] ?: asset('images/placeholder.png') }}" alt="{{ $category['name'] }}"
            class="w-full h-48 object-contain">
        <div class="p-4 text-center">
            <h2 class="text-xl font-semibold text-gray-800">{{ $category['name'] }}</h2>
            <p class="text-gray-600 mt-2">Посмотреть товары</p>
        </div>
    </a>
    @endforeach
</div>

<!-- Партнеры -->
<div class="py-16 overflow-hidden ">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center mb-12">Наши партнеры</h2>

        <div x-data="{
            partners: [
                { id: 1, logo: '{{ asset('assets/partners/1-zavod_litkom.png') }}', alt: 'Завод Литком' },
                { id: 2, logo: '{{ asset('assets/partners/abx-logo.png') }}', alt: 'ABX' },
                { id: 3, logo: '{{ asset('assets/partners/aston-logo.png') }}', alt: 'Aston' },
                { id: 4, logo: '{{ asset('assets/partners/ballu-logo.png') }}', alt: 'Ballu' },
                { id: 5, logo: '{{ asset('assets/partners/Defro.png') }}', alt: 'Defro' },
                { id: 6, logo: '{{ asset('assets/partners/echa-tech.png') }}', alt: 'Echa Tech' },
                { id: 7, logo: '{{ asset('assets/partners/esma-logo.svg') }}', alt: 'Esma' },
                { id: 8, logo: '{{ asset('assets/partners/etna-logo.jpg') }}', alt: 'Etna' },
                { id: 9, logo: '{{ asset('assets/partners/everest.svg')  }}', alt: 'Everest' },
                { id: 10, logo: '{{ asset('assets/partners/FireBird-logo.png') }}', alt: 'FireBird' },
                { id: 11, logo: '{{ asset('assets/partners/INVICTA-logo.png')  }}', alt: 'Invicta' },
                { id: 12, logo: '{{ asset('assets/partners/kratki-loga.png')  }}', alt: 'Kratki' },
                { id: 13, logo: '{{ asset('assets/partners/LOGO-KRATKI-PRO.webp') }}', alt: 'Kratki Pro' },
                { id: 14, logo: '{{ asset('assets/partners/logo-schiedel.png') }}', alt: 'Schiedel' },
                { id: 15, logo: '{{ asset('assets/partners/Logo-termofor.jpg') }}', alt: 'Termofor' },
                { id: 16, logo: '{{ asset('assets/partners/logo-top-mobile.svg') }}', alt: 'Esma2' },
                { id: 17, logo: '{{ asset('assets/partners/NMK-logo.svg') }}', alt: 'NMK' },
                { id: 18, logo: '{{ asset('assets/partners/nordpeis_logo.webp') }}', alt: 'nordpeis_logo' },
                { id: 19, logo: '{{ asset('assets/partners/realflame-logo.png') }}', alt: 'realflame' },
                { id: 20, logo: '{{ asset('assets/partners/royalflame-logo.png') }}', alt: 'royalflame' },
                { id: 21, logo: '{{ asset('assets/partners/steelheat-logo.jpg') }}', alt: 'steelheat' },
                { id: 22, logo: '{{ asset('assets/partners/vezuvii.svg') }}', alt: 'vezuvii' },
                { id: 23, logo: '{{ asset('assets/partners/Warmhaus-logo.jpg') }}', alt: 'Warmhaus' }
            ],
            currentIndex: 0,
            init() {
                setInterval(() => this.currentIndex = (this.currentIndex + 0.02) % this.partners.length, 10 )
            },
            getTransform() {
                return `translateX(-${this.currentIndex * (100 / this.partners.length)}%)`
            }
        }" class="relative h-32">
            <div class="absolute top-0 left-0 w-full h-full flex items-center">
                <div class="flex items-center" :style="`transform: ${getTransform()}; width: ${partners.length * 100}%`">
                    <template x-for="(partner, index) in [...partners, ...partners]" :key="`partner-${index}-${partner.id}`">
                        <div class="flex-shrink-0 w-[12.5%] px-4">
                            <div class="   transition h-25 flex items-center justify-center">
                                <img :src="partner.logo" :alt="partner.alt" class="object-cover">
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
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
      "streetAddress": "ул. Красноармейская, д. 1, к. 2",
      "addressLocality": "Самара",
      "postalCode": "443099",
      "addressCountry": "RU"
    },
    "contactPoint": {
      "@type": "ContactPoint",
      "telephone": "+79198055747",
      "email": "info@example.com",
      "contactType": "customer service"
    }
  }
}
</script>
@endpush