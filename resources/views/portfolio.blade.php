@extends('layouts.app')

@section('seo_title', $seoData['title'])
@section('seo_description', $seoData['description'])
@section('seo_keywords', $seoData['keywords'])

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <header class="mb-12 text-center">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Портфолио Дом каминов</h1>
        <p class="text-xl text-gray-700 max-w-4xl mx-auto leading-relaxed">
            Дом каминов гордится каждым проектом — от уютных дачных печей до премиальных каминов в элитных коттеджах.
            <span class="text-orange font-semibold">Более 550 установок</span> по Самаре, Тольятти и области.
            Все работы выполнены <span class="font-medium">под ключ</span>: от проектирования до запуска с гарантией 3 года.
        </p>
    </header>
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($portfolioItems as $item)
            <article id="work-{{ $loop->iteration }}" class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="overflow-hidden">
                    @if($item->image_url)
                        <img src="{{ \Illuminate\Support\Str::startsWith($item->image_url, ['http://', 'https://']) ? $item->image_url : asset(ltrim($item->image_url, '/')) }}"
                             alt="{{ $item->title }}"
                             class="w-full h-56 object-contain group-hover:scale-105 transition-transform duration-300"
                             loading="lazy"
                             decoding="async">
                    @endif
                </div>
                <div class="p-5">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $item->title }}</h3>
                    @if($item->subtitle)
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $item->subtitle }}</p>
                    @endif
                </div>
            </article>
        @empty
            <div class="col-span-full text-center text-gray-600">Пока нет работ.</div>
        @endforelse
    </section>

</div>
@endsection

@push('structured-data')
@php
    $itemListElement = $portfolioItems->values()->map(function ($item, $index) {
        $payload = [
            '@type' => 'CreativeWork',
            'name' => $item->title,
            'description' => $item->subtitle,
            'url' => url()->current() . '#work-' . ($index + 1),
        ];

        if ($item->image_url) {
            $payload['image'] = \Illuminate\Support\Str::startsWith($item->image_url, ['http://', 'https://'])
                ? $item->image_url
                : asset(ltrim($item->image_url, '/'));
        }

        $payload = array_filter($payload, fn($v) => !is_null($v) && $v !== '');

        return [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'item' => $payload,
        ];
    })->all();

    $jsonLd = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => 'Портфолио Дом каминов — установка каминов и печей',
        'description' => 'Дом каминов: реальные фото наших работ по монтажу каминов, печей и дымоходов в Самаре и области. Гарантия 3 года, выезд в день заказа.',
        'numberOfItems' => $portfolioItems->count(),
        'itemListElement' => $itemListElement,
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush
