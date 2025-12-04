@extends('layouts.app')

@section('title', $category ? 'Купить ' . $category : 'Каталог товаров')

@section('content')
<h1 class="text-3xl font-bold mb-6">
    {{ $category ? 'Купить ' . $category : 'Каталог товаров' }}
</h1>

{{-- Режим 1: Плитка категорий --}}
@if($showCategories ?? false)

    @php
        $desiredOrder = [
            'Биокамины','Электроочаги','Порталы','Каминокомплекты',
            'Топки','Печи-камины','Газовые топки, уличные нагреватели','Дымоходы','Вентиляция',
        ];

        $collection = collect($categories ?? []);

        $sorted = collect($desiredOrder)
            ->map(fn($name) => $collection->firstWhere('name', $name))
            ->filter()
            ->merge($collection->reject(fn($c) => in_array($c['name'], $desiredOrder, true)));
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($sorted as $cat)
            <a href="{{ url('/catalog') . '?category=' . urlencode($cat['name']) }}"
               class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <img src="{{ asset($cat['image_url']) }}"
                     alt="{{ $cat['name'] }}"
                     class="w-full h-48 object-contain bg-white">
                <div class="p-4 text-center">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $cat['name'] }}</h2>
                    <p class="text-gray-600 mt-2">Посмотреть товары</p>
                </div>
            </a>
        @endforeach
    </div>

@else

    {{-- Режим 2: Категория выбрана — фильтры + товары --}}
    <div class="flex flex-col md:flex-row gap-5">

        <!-- Фильтры -->
        @include('components.filters', [
            'proizvoditeli' => $proizvoditeli,
            'v_nalichii_options' => $v_nalichii_options,
            'currentFilters' => request()->all()
        ])

        <!-- Сетка товаров -->
        <div class="w-4/5">
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-3">
                @foreach($products as $product)
                    @include('components.product-card', ['product' => $product])
                @endforeach
            </div>

            <!-- Пагинация -->
            <div class="mt-8">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>

    </div>

@endif

@php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;

$seoViewCandidates = [];

if (!empty($category)) {
    // карта: точное имя категории → имя вьюхи без префикса 'seo.catalog.'
    $seoMap = [
        'Каминное/печное литье' => 'kaminnoe-pechnoe-litye', // или 'lite' — подгони под свой файл
    ];

    $slug = Str::slug($category, '-');

    $seoViewCandidates = array_filter([
        isset($seoMap[$category]) ? 'seo.catalog.' . $seoMap[$category] : null,
        'seo.catalog.' . $slug,
        'seo.catalog.' . $category, // если сделаешь файл прямо с кириллицей в имени
    ]);
}

$existingSeoViews = collect(array_merge($seoViewCandidates, ['seo.catalog.default']))
    ->filter(fn($v) => View::exists($v))
    ->values();
@endphp

<div class="mt-12 bg-white p-6 rounded-lg shadow-sm prose max-w-none">
    @if($existingSeoViews->isNotEmpty())
        @include($existingSeoViews->first())
    @else
        @include('seo.catalog.default')
    @endif
</div>

@endsection
