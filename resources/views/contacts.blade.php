{{-- resources/views/contact.blade.php --}}
@extends('layouts.app')

@section('seo_title', $seoData['title'])
@section('seo_description', $seoData['description'])
@section('seo_keywords', $seoData['keywords'])

@section('content')
<div class="section p-6 sm:p-8 md:p-10 space-y-8">
    <div class="space-y-3">
        <div class="eyebrow inline-flex">Контакты</div>
        <h1 class="section-title text-3xl sm:text-4xl">Свяжитесь с нами</h1>
        <p class="section-lead text-base">
            Ответим на вопросы по подбору, монтажу и доставке. Работаем без выходных с 10:00 до 19:30.
        </p>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <div class="surface p-6 space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-amber-50 border border-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm text-slate-500">Телефон</span>
                    <a href="tel:+79179535850" class="text-lg font-semibold text-slate-900 hover:text-orange">
                        +7 (917) 953-58-50
                    </a>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-amber-50 border border-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm text-slate-500">Email</span>
                    <a href="mailto:info@d-kaminov.com" class="text-lg font-semibold text-slate-900 hover:text-orange">
                        info@d-kaminov.com
                    </a>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-amber-50 border border-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <span class="text-sm text-slate-500">Адрес</span>
                    <p class="text-slate-900 font-semibold">
                        ТЦ Интермебель, Московское шоссе 16 км, 1в ст2, 2 этаж, Самара
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-amber-50 border border-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <span class="text-sm text-slate-500">График</span>
                    <p class="text-slate-900 font-semibold">Пн–Вс: 10:00 – 19:30, без выходных</p>
                </div>
            </div>
        </div>

        <div class="surface p-6 flex flex-col gap-4">
            <h3 class="text-xl font-semibold text-slate-900">Приезжайте в шоурум</h3>
            <p class="text-slate-700">
                Подберём портал, очаг и дымоход под ваш интерьер, покажем материалы и примеры готовых работ.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="tel:+79179535850" class="btn-primary">Позвонить</a>
                <button class="btn-ghost" type="button" onclick="window.dispatchEvent(new CustomEvent('open-callback'))">
                    Заказать звонок
                </button>
            </div>
            <p class="text-sm text-slate-500">
                Можно договориться о выезде на объект в день обращения.
            </p>
        </div>
    </div>

    <div class="surface p-4 sm:p-6">
        <div class="flex items-center justify-between gap-3 mb-3">
            <div>
                <p class="eyebrow mb-2">Как добраться</p>
                <h3 class="text-xl font-semibold text-slate-900">Мы на карте</h3>
                <p class="text-sm text-slate-600">Самара, Дом каминов (ТЦ Интермебель), Московское шоссе 16 км, 1в ст2, 2 этаж</p>
            </div>
            <a class="btn-ghost text-sm" target="_blank" rel="noopener noreferrer"
               href="https://yandex.ru/maps/?text=%D0%94%D0%BE%D0%BC%20%D0%BA%D0%B0%D0%BC%D0%B8%D0%BD%D0%BE%D0%B2%20%D0%A1%D0%B0%D0%BC%D0%B0%D1%80%D0%B0%2C%20%D0%9C%D0%BE%D1%81%D0%BA%D0%BE%D0%B2%D1%81%D0%BA%D0%BE%D0%B5%20%D1%88%D0%BE%D1%81%D1%81%D0%B5%2016%20%D0%BA%D0%BC%2C%201%D0%B2%20%D1%81%D1%822">
                Открыть в Яндекс.Картах
            </a>
        </div>
        <div class="overflow-hidden rounded-xl border border-gray-200">
            <div id="yandex-map" class="w-full h-[420px]"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://api-maps.yandex.ru/2.1/?apikey=401cc2e3-f3c7-4733-b687-de104b22b839&lang=ru_RU" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const initMap = () => {
            const mapContainer = document.getElementById('yandex-map');
            if (!mapContainer || !window.ymaps) return;

            const address = 'Дом каминов, Самара, Московское шоссе 16 км, 1в ст2';

            ymaps.ready(() => {
                const map = new ymaps.Map('yandex-map', {
                    center: [53.249404, 50.212019], // запасной центр Самары
                    zoom: 17,
                    controls: ['zoomControl', 'fullscreenControl']
                });

                ymaps.geocode(address).then(result => {
                    const first = result.geoObjects.get(0);
                    if (!first) return;

                    const coords = first.geometry.getCoordinates();
                    const placemark = new ymaps.Placemark(coords, {
                        balloonContent: '<strong>Дом каминов</strong><br>' + address
                    }, {
                        preset: 'islands#redIcon'
                    });

                    map.geoObjects.add(placemark);
                    map.setCenter(coords);
                });
            });
        };

        // Скрипт API грузится с defer, поэтому ymaps уже должен быть доступен
        initMap();
    });
</script>
@endpush

@push('structured-data')
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "D-Kaminov",
        "telephone": "+79179535850",
        "email": "info@d-kaminov.com",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "ТЦ Интермебель, Московское шоссе 16 км, 1в ст2, 2 этаж",
            "addressLocality": "Самара",
            "postalCode": "443095",
            "addressCountry": "RU"
        },
        "openingHours": "Mo-Su 10:00-19:30"
    }
</script>
@endpush
