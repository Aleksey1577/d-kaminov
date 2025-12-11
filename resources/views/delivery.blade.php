@extends('layouts.app')

@section('seo_title', $seoData['title'])
@section('seo_description', $seoData['description'])
@section('seo_keywords', $seoData['keywords'])

@section('content')
<div class="section p-6 sm:p-8 md:p-10 space-y-10">
    <div class="text-center space-y-3">
        <div class="eyebrow inline-flex">Информация</div>
        <h1 class="section-title text-3xl sm:text-4xl">Доставка и оплата</h1>
        <p class="section-lead text-base mx-auto">
            Привозим оборудование по Самаре и области, помогаем с разгрузкой и установкой.
            Оплата удобным способом — наличными, картой онлайн или по реквизитам.
        </p>
    </div>

    <div>
        <h2 class="text-2xl font-semibold text-slate-900 mb-6 text-center">Способы доставки</h2>
        <div class="grid md:grid-cols-2 gap-4">
            <div class="surface p-5">
                <h3 class="text-xl font-semibold text-slate-900">Доставка по Самаре</h3>
                <p class="mt-3 text-slate-700"><span class="font-semibold">Стоимость:</span> 1000,00 руб.</p>
                <p class="text-slate-700"><span class="font-semibold">Срок:</span> от 1 дня в зависимости от наличия товара.</p>
            </div>
            <div class="surface p-5">
                <h3 class="text-xl font-semibold text-slate-900">Доставка по области (до 50 км)</h3>
                <p class="mt-3 text-slate-700"><span class="font-semibold">Стоимость:</span> 1500,00 руб.</p>
                <p class="text-slate-700"><span class="font-semibold">Срок:</span> от 1 дня в зависимости от наличия товара.</p>
            </div>
            <div class="surface p-5">
                <h3 class="text-xl font-semibold text-slate-900">Область (свыше 50 км)</h3>
                <p class="mt-3 text-slate-700"><span class="font-semibold">Стоимость:</span> по согласованию с менеджером.</p>
                <p class="text-slate-700"><span class="font-semibold">Срок:</span> от 1 дня в зависимости от наличия товара.</p>
            </div>
            <div class="surface p-5">
                <h3 class="text-xl font-semibold text-slate-900">Самовывоз</h3>
                <p class="mt-3 text-slate-700"><span class="font-semibold">Стоимость:</span> бесплатно.</p>
                <p class="text-slate-700"><span class="font-semibold">Адрес:</span> г. Самара, ТЦ Интермебель, Московское шоссе 16 километр, 1в ст2, 2 этаж.</p>
                <p class="text-slate-700"><span class="font-semibold">Время:</span> будни с 10:00 до 19:30.</p>
            </div>
        </div>
    </div>

    <div>
        <h2 class="text-2xl font-semibold text-slate-900 mb-6 text-center">Способы оплаты</h2>
        <div class="grid md:grid-cols-3 gap-4">
            <div class="surface-quiet p-5 text-center">
                <p class="text-lg font-semibold text-slate-900">Оплата наличными</p>
                <p class="mt-2 text-slate-700">При получении товара.</p>
            </div>
            <div class="surface-quiet p-5 text-center">
                <p class="text-lg font-semibold text-slate-900">Картой онлайн</p>
                <p class="mt-2 text-slate-700">Безопасный платёж через сайт.</p>
            </div>
            <div class="surface-quiet p-5 text-center">
                <p class="text-lg font-semibold text-slate-900">Безналичный расчёт</p>
                <p class="mt-2 text-slate-700">Оплата по реквизитам.</p>
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
