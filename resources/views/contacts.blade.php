{{-- resources/views/contact.blade.php --}}
@extends('layouts.app')

@section('seo_title', $seoData['title'])
@section('seo_description', $seoData['description'])
@section('seo_keywords', $seoData['keywords'])

@section('content')
<div class="max-w-6xl mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6 text-black">Контакты</h1>
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Контакты слева -->
        <div class="md:w-1/2">
            <p class="mb-4 text-black">Свяжитесь с нами любым удобным способом:</p>
            <ul class="space-y-3 text-black">
                <li class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                    <a href="tel:+79198055747" class="hover:text-orange-600 hover:underline">+7 (919) 805-57-47</a>
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    <a href="mailto:info@d-kaminov.com" class="hover:text-orange-600 hover:underline">info@d-kaminov.com</a>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-orange-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <span>г. Самара, ТЦ Интермебель, Московское шоссе 16 км, 1в ст2, 2 этаж</span>
                </li>
                <li class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>Пн-Вс: 10:00 – 19:30, без выходных</span>
                </li>
            </ul>
        </div>

        <!-- Яндекс.Карта справа -->
        <div class="md:w-1/2">
            <div id="yandex-map" class="w-full h-96 rounded-xl shadow-lg border border-gray-200"></div>
        </div>
    </div>
</div>
@endsection

{{-- === Яндекс.Карты === --}}
@push('scripts')
<script src="https://api-maps.yandex.ru/2.1/?apikey=401cc2e3-f3c7-4733-b687-de104b22b839&lang=ru_RU" type="text/javascript"></script>
<script>
    // Ждём полной загрузки DOM и API
    window.addEventListener('load', function () {
        if (typeof ymaps === 'undefined') {
            console.error('Yandex Maps API не загрузился');
            return;
        }

        ymaps.ready(function () {
            const container = document.getElementById('yandex-map');
            if (!container) return;

            const map = new ymaps.Map(container, {
                center: [53.2245, 50.1682],  // ТЦ Интермебель
                zoom: 17,
                controls: ['zoomControl']
            });

            const placemark = new ymaps.Placemark([53.2245, 50.1682], {
                hintContent: 'D-Kaminov',
                balloonContent: `
                    <strong>D-Kaminov</strong><br>
                    ТЦ Интермебель, 2 этаж<br>
                    Московское шоссе 16 км, 1в ст2<br>
                    <br>
                    +7 (919) 805-57-47<br>
                    Пн-Вс: 10:00–19:30
                `
            }, {
                preset: 'islands#orangeDotIcon'
            });

            map.geoObjects.add(placemark);
            map.setBounds(map.geoObjects.getBounds(), { checkZoomRange: true });
        });
    });
</script>
@endpush

@push('structured-data')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "D-Kaminov",
  "telephone": "+79198055747",
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