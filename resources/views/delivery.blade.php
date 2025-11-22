@extends('layouts.app')

@section('seo_title', $seoData['title'])
@section('seo_description', $seoData['description'])
@section('seo_keywords', $seoData['keywords'])

@section('content')
<div class="mx-auto">
    <h1 class="text-3xl font-bold mb-6 text-center">Доставка и оплата</h1>
    <div class="mb-10">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-center">Способы доставки</h2>
        <div class="flex flex-col space-y-6 max-w-6xl mx-auto">
            <div class="p-4 rounded-lg shadow-md bg-white mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Доставка по Самаре</h3>
                <p class="mt-2 text-gray-600"><span class="font-semibold">Стоимость:</span> 1000,00 руб.</p>
                <p class="text-gray-600"><span class="font-semibold">Срок доставки:</span> от 1 дня в зависимости от наличия товара.</p>
            </div>
            <div class="p-4 rounded-lg shadow-md bg-white mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Доставка по области (до 50 км от Самары)</h3>
                <p class="mt-2 text-gray-600"><span class="font-semibold">Стоимость:</span> 1500,00 руб.</p>
                <p class="text-gray-600"><span class="font-semibold">Срок доставки:</span> от 1 дня в зависимости от наличия товара.</p>
            </div>
            <div class="p-4 rounded-lg shadow-md bg-white mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Доставка по области (свыше 50 км от Самары)</h3>
                <p class="mt-2 text-gray-600"><span class="font-semibold">Стоимость:</span> по согласованию с менеджером.</p>
                <p class="text-gray-600"><span class="font-semibold">Срок доставки:</span> от 1 дня в зависимости от наличия товара.</p>
            </div>
            <div class="p-4 rounded-lg shadow-md bg-white">
                <h3 class="text-xl font-semibold text-gray-800">Самовывоз</h3>
                <p class="mt-2 text-gray-600"><span class="font-semibold">Стоимость:</span> Бесплатно</p>
                <p class="text-gray-600"><span class="font-semibold">Адрес:</span> г. Самара, ТЦ Интермебель, Московское шоссе 16 километр, 1в ст2, 2 этаж</p>
                <p class="text-gray-600"><span class="font-semibold">Режим работы:</span> будни с 10:00 до 19:30</p>
            </div>
        </div>
    </div>
    <div>
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-center">Способы оплаты</h2>
        <div class="flex flex-col md:flex-row gap-6 max-w-6xl mx-auto">
            <div class=" p-4 rounded-lg shadow-md bg-white flex-1 text-center">
                <p class="text-lg font-semibold text-gray-800">Оплата наличными</p>
                <p className="mt-2 text-gray-600">При получении товара</p>

            </div>
            <div class=" p-4 rounded-lg shadow-md bg-white flex-1 text-center">
                <p class="text-lg font-semibold text-gray-800">Картой онлайн</p>
                <p className="mt-2 text-gray-600">Безопасный платёж через сайт</p>

            </div>
            <div class=" p-4 rounded-lg shadow-md bg-white flex-1 text-center">
                <p class="text-lg font-semibold text-gray-800">Банковский перевод</p>
                <p className="mt-2 text-gray-600">Оплата по реквизитам</p>

            </div>
        </div>
    </div>
</div>
@endsection

@push('structured-data')
<script type="application/ld+json">
    {
        "@context": "https://schema.org ",
        "@type": "Service",
        "serviceType": "Доставка товаров",
        "provider": {
            "@type": "Organization",
            "name": "D-Kaminov"
        },
        "description": "{{ $seoData['description'] }}",
        "areaServed": {
            "@type": "City",
            "name": "Самара"
        }
    }
</script>
@endpush