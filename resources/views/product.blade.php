{{-- resources/views/product.blade.php --}}

@extends('layouts.app')

@section('seo_title', $seo['seo_title'])
@section('seo_description', $seo['seo_description'])
@section('seo_keywords', $seo['seo_keywords'])
@section('seo_image', asset($product->image_url))

@section('content')
<!-- JSON-LD структурированные данные -->
@include('seo.product-json-ld', ['product' => $product])

<div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
    <div class="flex flex-col lg:flex-row lg:items-start gap-6 lg:gap-10">
        <!-- Компонент с картинками -->
        <div class="w-full lg:w-1/2">
            <x-product-images :product="$product" />
        </div>

        <!-- Компонент с характеристиками и ценой -->
        <div class="w-full lg:w-1/2">
            <div class="flex flex-col-reverse lg:flex-row lg:items-start gap-6">
                <!-- Краткие характеристики (слева на десктопе, ниже цены на мобиле) -->
                <div class="lg:w-1/2">
                    <x-short-characteristics :product="$product" />
                </div>

                <!-- Цена и кнопка "В корзину" (справа на десктопе, выше характеристик на мобиле) -->
                <div class="lg:w-1/2">
                    <x-product-price :product="$product" :variants="$variants" />
                </div>
            </div>
        </div>
    </div>

    <!-- Табы -->
    <div class="mt-6">
        <x-product-tabs :product="$product" />
    </div>
</div>
@endsection