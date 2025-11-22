@extends('layouts.app')

@section('title', 'Избранное')

@section('content')
@php
    $hasProducts = $products->count() > 0;
@endphp

<h1 class="text-2xl font-semibold mb-6">Избранное</h1>

@if($hasProducts)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($products as $product)
            <div class="relative bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                {{-- Кнопка удалить из избранного --}}
                <form action="{{ route('favorites.remove', $product->product_id) }}" method="POST"
                      onsubmit="sessionStorage.setItem('scrollPos', window.pageYOffset); return true;"
                      class="absolute top-3 right-3 z-10">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="bg-red-600 text-white p-2 rounded-full hover:bg-red-700 shadow focus:outline-none"
                            title="Убрать из избранного">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                  clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>

                {{-- Ваша карточка товара --}}
                <x-product-card :product="$product" />
            </div>
        @endforeach
    </div>
@else
    <div class="bg-white p-6 rounded shadow text-center">
        <p class="text-gray-600 mb-4">Ваше избранное пусто.</p>
        <a href="{{ route('catalog') }}"
           class="inline-block px-6 py-3 bg-orange text-white rounded hover:bg-orange-700 transition">
            Перейти в каталог
        </a>
    </div>
@endif
@endsection
