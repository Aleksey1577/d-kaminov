@extends('layouts.app')

@section('title', 'Избранное')
@section('seo_title', 'Избранные товары | Дом каминов')
@section('seo_description', 'Сохранённые избранные камины, печи и аксессуары — вернитесь и оформите заказ в Дом каминов.')
@section('seo_robots', 'noindex,follow')

@section('content')
@php
    $hasProducts = $products->count() > 0;
@endphp

<div class="section p-5 sm:p-6 md:p-8 space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="eyebrow">Избранное</div>
            <h1 class="section-title text-2xl sm:text-3xl">Избранные товары</h1>
            <p class="text-sm text-slate-600">Сохранённые позиции для быстрого возврата.</p>
        </div>
        @if($hasProducts)
            <a href="{{ route('catalog') }}" class="btn-ghost text-sm">Добавить ещё</a>
        @endif
    </div>

    @if($hasProducts)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $product)
                <div class="relative surface overflow-hidden">

                    <form action="{{ route('favorites.remove', $product->product_id) }}" method="POST"
                          onsubmit="sessionStorage.setItem('scrollPos', window.pageYOffset); return true;"
                          class="absolute top-3 right-3 z-10">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-9 h-9 flex items-center justify-center rounded-full bg-red-50 text-red-600 hover:bg-red-100 shadow-sm"
                                title="Убрать из избранного">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                      clip-rule="evenodd" />
                            </svg>
                        </button>
                    </form>

                    <x-product-card :product="$product" />
                </div>
            @endforeach
        </div>
    @else
        <div class="surface p-6 text-center">
            <p class="text-slate-600 mb-4">Ваше избранное пусто.</p>
            <a href="{{ route('catalog') }}" class="btn-primary">Перейти в каталог</a>
        </div>
    @endif
</div>
@endsection
