@extends('layouts.app')

@section('content')
@php
    // Хелпер форматирования цены: вывод без запятых, с пробелами
    $fmt = fn($n) => number_format((float)$n, 0, '', ' ');

    $cart = $cart ?? session('cart', []);
    $cartIsEmpty = empty($cart);
@endphp

<h1 class="text-2xl font-semibold mb-6">Корзина</h1>

@if($cartIsEmpty)
    <div class="bg-white p-6 rounded shadow text-center">
        <p class="text-gray-600 mb-4">Ваша корзина пуста.</p>
        <a href="{{ route('catalog') }}" class="inline-block px-6 py-3 bg-orange text-white rounded hover:bg-orange-white transition">
            Перейти в каталог
        </a>
    </div>
@else
    <div class="bg-white rounded-lg shadow overflow-hidden">

        {{-- Заголовки на десктопе --}}
        <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-3 bg-gray-50 text-sm font-medium text-gray-600">
            <div class="col-span-6">Товар</div>
            <div class="col-span-2 text-right">Цена</div>
            <div class="col-span-2 text-center">Количество</div>
            <div class="col-span-2 text-right">Сумма</div>
        </div>

        <div class="divide-y">
            @php $grand = 0; @endphp

            @foreach($cart as $lineId => $item)
                @php
                    $qty   = (int)($item['quantity'] ?? 1);
                    $price = (float)($item['price'] ?? 0);
                    $sum   = $qty * $price;
                    $grand += $sum;

                    // Определяем slug:
                    $slug = $item['slug'] ?? null;

                    // если slug нет (старые корзины) — достаём из БД
                    if (!$slug) {
                        $p = \App\Models\Product::where('product_id', $item['parent_id'] ?? $item['product_id'])->first();
                        $slug = $p?->slug;
                    }
                @endphp

                <div class="grid grid-cols-12 gap-4 px-4 md:px-6 py-4 items-center">

                    {{-- Товар --}}
                    <div class="col-span-12 md:col-span-6 flex items-center gap-4">
                        @if($slug)
                            <a href="{{ route('product', $slug) }}" class="flex-shrink-0">
                                <img src="{{ $item['image_url'] ?? asset('images/placeholder.png') }}"
                                     class="w-16 h-16 object-contain rounded border" alt="">
                            </a>
                        @else
                            <div class="flex-shrink-0">
                                <img src="{{ $item['image_url'] ?? asset('images/placeholder.png') }}"
                                     class="w-16 h-16 object-contain rounded border" alt="">
                            </div>
                        @endif

                        <div class="min-w-0">
                            <div class="font-medium text-gray-800 truncate">
                                @if($slug)
                                    <a href="{{ route('product', $slug) }}" class="hover:text-orange">
                                        {{ $item['naimenovanie'] ?? 'Товар' }}
                                    </a>
                                @else
                                    {{ $item['naimenovanie'] ?? 'Товар' }}
                                @endif
                            </div>

                            @if(!empty($item['sku']))
                                <div class="text-xs text-gray-500 mt-1">Артикул: {{ $item['sku'] }}</div>
                            @endif
                        </div>
                    </div>

                    {{-- Цена --}}
                    <div class="col-span-6 md:col-span-2 text-right text-gray-800 font-medium">
                        {{ $fmt($price) }} ₽
                    </div>

                    {{-- Количество --}}
                    <div class="col-span-3 md:col-span-2 text-center">
                        <span class="inline-flex items-center justify-center w-12 h-9 bg-gray-50 rounded border text-gray-800">
                            {{ $qty }}
                        </span>
                    </div>

                    {{-- Сумма и кнопка удалить --}}
                    <div class="col-span-3 md:col-span-2 text-right">
                        <div class="text-gray-900 font-semibold">{{ $fmt($sum) }} ₽</div>

                        <form action="{{ route('cart.remove', $lineId) }}" method="POST" class="inline-block mt-2"
                              onsubmit="return confirm('Удалить этот товар из корзины?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:text-red-700 text-sm">Удалить</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Итог --}}
        <div class="px-4 md:px-6 py-4 bg-gray-50 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div class="text-gray-600">
                Всего товаров: {{ collect($cart)->sum('quantity') }}
            </div>

            <div class="text-xl font-semibold text-gray-900">
                Итого: {{ $fmt($grand) }} ₽
            </div>
        </div>
    </div>

    {{-- Кнопки --}}
    <div class="mt-6 flex flex-col sm:flex-row gap-3">
        <a href="{{ route('catalog') }}"
           class="px-6 py-3 bg-gray-100 text-gray-800 rounded hover:bg-gray-200 transition text-center">
            Продолжить покупки
        </a>

        <a href="{{ route('checkout') }}"
           class="px-6 py-3 bg-orange text-white rounded hover:bg-orange-700 transition text-center sm:ml-auto">
            Оформить заказ
        </a>
    </div>
@endif
@endsection
