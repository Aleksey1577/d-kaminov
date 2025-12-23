@extends('layouts.app')

@section('title', $category ? 'Купить ' . $category . ' в Самаре' : 'Каталог Дом каминов')
@section('seo_title', $category ? 'Купить ' . $category . ' в Самаре' : 'Каталог Дом каминов — камины и печи')
@section('seo_description', $category
    ? 'Купить ' . $category . ' в Самаре и с доставкой по России. Цены, наличие, фильтры и характеристики.'
    : 'Каталог Дом каминов: камины, топки, печи и аксессуары. Фильтры по цене, бренду и наличию, доставка и монтаж под ключ.'
)
@section('seo_keywords', $category
    ? implode(', ', array_filter([
        $category . ' купить',
        $category . ' Самара',
        'каталог ' . $category,
        $category . ' цены',
        'камины Самара',
        'дом каминов',
        'дом каминов Самара',
        'Дом каминов',
    ]))
    : 'дом каминов каталог, каталог каминов, каталог печей, дом каминов Самара, камины Самара, печи Самара, биокамины, электрокамины, топки, дымоходы, купить камин, купить печь'
)

@section('content')
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
    <div class="space-y-2">
        <div class="eyebrow">{{ $category ? 'Категория' : 'Каталог' }}</div>
        <h1 class="section-title">
            {{ $category ? 'Купить ' . $category . ' в Самаре' : 'Каталог Дом каминов' }}
        </h1>
        <p class="section-lead text-base">
            Актуальные цены, наличие и удобные фильтры по бренду, стоимости и характеристикам.
        </p>
    </div>

    @if(!($showCategories ?? false))
        <form method="GET" action="{{ route('catalog', $categorySlug ? ['category' => $categorySlug] : []) }}" class="flex items-center gap-2 sm:pb-1">
            @foreach(request()->except(['sort','page','category']) as $k => $v)
                @if(is_array($v))
                    @foreach($v as $vv)
                        <input type="hidden" name="{{ $k }}[]" value="{{ $vv }}">
                    @endforeach
                @else
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endif
            @endforeach

            <label for="sort" class="text-sm text-slate-600 whitespace-nowrap">Сортировка:</label>
            <select name="sort" id="sort"
                class="rounded-lg border border-amber-200 px-3 py-2 bg-white focus:border-orange focus:ring focus:ring-orange/20"
                onchange="this.form.submit()">
                <option value="" @selected(request()->sort === null || request()->sort === '')>По названию</option>
                <option value="price_asc" @selected(request()->sort === 'price_asc')>Цена (по возрастанию)</option>
                <option value="price_desc" @selected(request()->sort === 'price_desc')>Цена (по убыванию)</option>
                <option value="new_desc" @selected(request()->sort === 'new_desc')>Сначала новые</option>
                <option value="new_asc" @selected(request()->sort === 'new_asc')>Сначала старые</option>
            </select>
        </form>
    @endif
</div>

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
            <a href="{{ route('catalog', ['category' => $cat['slug']]) }}"
               class="surface overflow-hidden hover:-translate-y-1 transition-transform duration-200 flex flex-col">
                <img src="{{ asset($cat['image_url']) }}"
                     alt="{{ $cat['name'] }}"
                     class="w-full h-48 object-contain bg-white"
                     loading="lazy"
                     decoding="async">
                <div class="p-5 text-center space-y-2">
                    <h2 class="text-xl font-semibold text-slate-900">{{ $cat['name'] }}</h2>
                    <p class="text-slate-600">Посмотреть товары</p>
                </div>
            </a>
        @endforeach
    </div>

@else

    <div class="flex flex-col md:flex-row gap-6">

        @include('components.filters', [
            'proizvoditeli' => $proizvoditeli,
            'v_nalichii_options' => $v_nalichii_options,
            'currentFilters' => request()->all(),
            'categoryName' => $category,
            'categorySlug' => $categorySlug,
        ])

        <div class="flex-1">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4">
                @foreach($products as $product)
                    @include('components.product-card', ['product' => $product])
                @endforeach
            </div>

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

<div class="mt-12 section p-6 sm:p-7 prose max-w-none">
    @if($existingSeoViews->isNotEmpty())
        @include($existingSeoViews->first())
    @else
        @include('seo.catalog.default')
    @endif
</div>

@endsection
