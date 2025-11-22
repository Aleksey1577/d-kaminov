{{--  /resources/views/product.blade.php  --}}

@extends('layouts.app')

@section('seo_title', $seo['seo_title'])
@section('seo_description', $seo['seo_description'])
@section('seo_keywords', $seo['seo_keywords'])
@section('seo_image', asset($product->image_url))

@section('content')
    <!-- JSON-LD структурированные данные -->
    @include('components.seo.product-json-ld', ['product' => $product])

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-wrap lg:flex-nowrap justify-between gap-8">
            <!-- Компонент с картинками (шире) -->
            <div class="flex-1 lg:w-1/2">
                <x-product-images :product="$product" />
            </div>

            <!-- Компонент с характеристиками и ценой -->
            <div class="flex-1 lg:w-1/2">
                <div class="flex justify-between">
                    <!-- Компонент с характеристиками -->
                    <x-short-characteristics :product="$product" />

                    <!-- Компонент с ценой и кнопкой "В корзину" -->
                    <x-product-price :product="$product" :variants="$variants" />
                </div>
            </div>
        </div>

        <!-- Табы -->
        <x-product-tabs :product="$product" />
    </div>
@endsection