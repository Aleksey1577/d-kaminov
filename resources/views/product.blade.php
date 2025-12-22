@extends('layouts.app')

@section('seo_title', $meta['seo_title'] ?? '')
@section('seo_description', $meta['seo_description'] ?? '')
@section('seo_keywords', $meta['seo_keywords'] ?? '')
@section('seo_image', asset($product->image_url))

@section('content')
@if(!isset($seo) || !is_object($seo) || !method_exists($seo, 'render'))
    @include('seo.product-json-ld', ['product' => $product])
@endif

<div class="section p-6 sm:p-8">
    <div class="mb-6 space-y-2">
        <div class="eyebrow">Товар</div>
        <h1 class="section-title text-3xl sm:text-4xl leading-tight">
            {{ $product->naimenovanie }}
        </h1>
        <p class="text-sm text-slate-600">
            Купить {{ $product->naimenovanie }} в Дом каминов в Самаре с доставкой по РФ.
        </p>
        @if($product->sku)
            <p class="text-sm text-slate-600">Артикул: {{ $product->sku }}</p>
        @endif
    </div>

    <div class="flex flex-col lg:flex-row lg:items-start gap-6 lg:gap-10">

        <div class="w-full lg:w-1/2">
            <x-product-images :product="$product" />
        </div>

        <div class="w-full lg:w-1/2">
            <div class="flex flex-col-reverse lg:flex-row lg:items-start gap-6">

                <div class="lg:w-1/2">
                    <x-short-characteristics :product="$product" />
                </div>

                <div class="lg:w-1/2">
                    <x-product-price :product="$product" :variants="$variants" />
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 surface-quiet p-4 sm:p-6">
        <x-product-tabs :product="$product" />
    </div>
</div>
@endsection
