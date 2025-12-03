@extends('layouts.app')

@section('seo_title', $seoData['title'])
@section('seo_description', $seoData['description'])
@section('seo_keywords', $seoData['keywords'])

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <header class="mb-12 text-center">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Наши работы</h1>
        <p class="text-xl text-gray-700 max-w-4xl mx-auto leading-relaxed">
            Мы гордимся каждым проектом — от уютных дачных печей до премиальных каминов в элитных коттеджах.
            <span class="text-orange font-semibold">Более 550 установок</span> по Самаре, Тольятти и области.
            Все работы выполнены <span class="font-medium">под ключ</span>: от проектирования до запуска с гарантией 3 года.
        </p>
    </header>

@php
    $works = [
        ['image' => 'work1.jpg',  'title' => 'Дымоход', 'desc' => 'Коттедж. Дымоход из нержавеющей стали AISI 430 с усиленной тягой и теплоизоляцией.'],
        ['image' => 'work2.jpg',  'title' => 'Угловая топка', 'desc' => 'Загородный дом. Дровяная угловая топка с КПД 78%, обогрев до 80 м².'],
        ['image' => 'work3.jpg',  'title' => 'Угловая топка', 'desc' => 'Загородный дом. Угловая дровяная топка с панорамным стеклом — компактное размещение, эффект живого огня, обогрев до 40 м².'],
        ['image' => 'work4.jpg',  'title' => 'Топка', 'desc' => 'Загородный дом. Настенная топка с LED-имитацией пламени, пульт ДУ, обогрев до 25 м².'],
        ['image' => 'work5.jpg',  'title' => 'Угловая топка', 'desc' => 'Коттедж. Дровяная угловая топка с кирпичной облицовкой, чугунной дверцей и системой "чистое стекло".'],
        ['image' => 'work6.jpg',  'title' => 'Топка', 'desc' => 'Загородный дом. Металлическая топка с панорамным стеклом — современный дизайн, экономия пространства.'],
        ['image' => 'work7.jpg',  'title' => 'Топка', 'desc' => 'Загородный дом. Чугунная топка с варочной плитой — готовим и греемся одновременно.'],
        ['image' => 'work8.jpg',  'title' => 'Печь камин', 'desc' => 'Загородный дом. Двухсторонняя печь-камин — огонь виден с двух сторон, создаёт атмосферу.'],
        ['image' => 'work9.jpg',  'title' => 'Угловая топка', 'desc' => 'Загородный дом. Компактная угловая электрокамин-топка с 3D-эффектом пламени, безопасна для детей.'],
        ['image' => 'work11.jpg', 'title' => 'Угловая топка', 'desc' => 'Элитный коттедж. Итальянская угловая топка, мраморный портал, автоматика и дистанционное управление.'],
        ['image' => 'work12.jpg', 'title' => 'Угловая топка', 'desc' => 'Беседка в загородном доме. Угловая топка с функциями гриля и коптильни — многофункциональный уличный очаг.'],
        ['image' => 'work13.jpg', 'title' => 'Топка', 'desc' => 'Сруб в загородной зоне. Топка с дымоходом через крышу, усиленная теплоизоляция, полная пожаробезопасность.'],
        ['image' => 'work14.jpg', 'title' => 'Биокамин', 'desc' => 'Лофт-апартаменты. Линейный биокамин длиной 1.2 м — эффект "живого огня" по всей длине.'],
        ['image' => 'work15.jpg', 'title' => 'Угловая топка', 'desc' => 'Загородный дом без газа. Угловая дровяная топка с водяным контуром — обогрев дома + горячая вода.'],
        ['image' => 'work16.jpg', 'title' => 'Печь камин с дымоходом', 'desc' => 'Загородный дом. Встроенная печь-камин с дымоходом в нише — идеально вписана в интерьер, экономия места.'],
        ['image' => 'work17.jpg', 'title' => 'Печь камин с дымоходом', 'desc' => 'Вилла в загородной зоне. Печь-камин с трёхсторонним остеклением — обзор пламени 270°, премиум-класс.'],
    ];
@endphp

    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($works as $work)
        <article class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
            <div class="overflow-hidden">
                <img src="{{ asset('assets/work/' . $work['image']) }}"
                    alt="{{ $work['title'] }}"
                    class="w-full h-56 object-contain group-hover:scale-105 transition-transform duration-300">
            </div>
            <div class="p-5">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $work['title'] }}</h3>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $work['desc'] }}</p>
            </div>
        </article>
        @endforeach
    </section>


</div>
@endsection

@push('structured-data')
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "ItemList",
        "name": "Портфолио ECHA + TECH — Установка каминов и печей",
        "description": "Реальные фото наших работ по монтажу каминов, печей и дымоходов в Самаре и области. Гарантия 3 года, выезд в день заказа.",
        "numberOfItems": {
            {
                count($works)
            }
        },
        "itemListElement": [
            @foreach($works as $index => $work) {
                "@type": "CreativeWork",
                "position": {
                    {
                        $index + 1
                    }
                },
                "name": "{{ $work['title'] }}",
                "description": "{{ $work['desc'] }}",
                "image": "{{ asset('assets/work/' . $work['image']) }}",
                "url": "{{ url()->current() }}#work-{{ $index + 1 }}"
            } {
                {
                    $loop - > last ? '' : ','
                }
            }
            @endforeach
        ]
    }
</script>
@endpush