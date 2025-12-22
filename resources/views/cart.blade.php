@extends('layouts.app')

@section('title', 'Корзина')
@section('seo_title', 'Корзина товаров | Дом каминов')
@section('seo_description', 'Проверьте и оформите заказ на камины, печи и аксессуары в интернет-магазине Дом каминов.')
@section('seo_robots', 'noindex,follow')

@section('content')
@php
    // Хелпер форматирования цены: вывод без запятых, с пробелами
    $fmt = fn($n) => number_format((float)$n, 0, '', ' ');

    $cart = $cart ?? session('cart', []);
    $cartIsEmpty = empty($cart);
@endphp

<div class="section p-5 sm:p-6 md:p-8 space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="eyebrow">Корзина</div>
            <h1 class="section-title text-2xl sm:text-3xl">Корзина</h1>
            <p class="text-sm text-slate-600">Проверьте состав заказа перед оформлением.</p>
        </div>
        @if(!$cartIsEmpty)
            <a href="{{ route('catalog') }}" class="btn-ghost text-sm">Добавить ещё</a>
        @endif
    </div>

    @if($cartIsEmpty)
        <div class="surface p-6 text-center">
            <p class="text-slate-600 mb-4">Ваша корзина пуста.</p>
            <a href="{{ route('catalog') }}" class="btn-primary">Перейти в каталог</a>
        </div>
    @else
        <div class="surface overflow-hidden">

            <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-3 bg-amber-50/60 text-sm font-semibold text-slate-700">
                <div class="col-span-6">Товар</div>
                <div class="col-span-2 text-right">Цена</div>
                <div class="col-span-2 text-center">Количество</div>
                <div class="col-span-2 text-right">Сумма</div>
            </div>

            <div class="divide-y divide-gray-100">
                @php $grand = 0; @endphp

                @foreach($cart as $lineId => $item)
                    @php
                        $qty   = (int)($item['quantity'] ?? 1);
                        $price = (float)($item['price'] ?? 0);
                        $sum   = $qty * $price;
                        $grand += $sum;
                        $slug = $item['slug'] ?? null;
                        if (!$slug) {
                            $p = \App\Models\Product::where('product_id', $item['parent_id'] ?? $item['product_id'])->first();
                            $slug = $p?->slug;
                        }
                    @endphp

                    <div class="grid grid-cols-12 gap-4 px-4 md:px-6 py-4 items-center">

                        <div class="col-span-12 md:col-span-6 flex items-center gap-4">
                            @if($slug)
                                <a href="{{ route('product', $slug) }}" class="flex-shrink-0">
                                    <img src="{{ $item['image_url'] ?? asset('assets/placeholder.png') }}"
                                         class="w-16 h-16 object-contain rounded border bg-white"
                                         alt=""
                                         loading="lazy"
                                         decoding="async">
                                </a>
                            @else
                                <div class="flex-shrink-0">
                                    <img src="{{ $item['image_url'] ?? asset('assets/placeholder.png') }}"
                                         class="w-16 h-16 object-contain rounded border bg-white"
                                         alt=""
                                         loading="lazy"
                                         decoding="async">
                                </div>
                            @endif

                            <div class="min-w-0">
                                <div class="font-semibold text-slate-900 truncate">
                                    @if($slug)
                                        <a href="{{ route('product', $slug) }}" class="hover:text-orange">
                                            {{ $item['naimenovanie'] ?? 'Товар' }}
                                        </a>
                                    @else
                                        {{ $item['naimenovanie'] ?? 'Товар' }}
                                    @endif
                                </div>

                                @if(!empty($item['sku']))
                                    <div class="text-xs text-slate-500 mt-1">Артикул: {{ $item['sku'] }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-span-6 md:col-span-2 text-right text-slate-900 font-semibold">
                            {{ $fmt($price) }} ₽
                        </div>

                        <div class="col-span-3 md:col-span-2 text-center">
                            <span class="inline-flex items-center justify-center w-12 h-9 bg-amber-50 rounded border border-amber-100 text-slate-800">
                                {{ $qty }}
                            </span>
                        </div>

                        <div class="col-span-3 md:col-span-2 text-right">
                            <div class="text-slate-900 font-bold">{{ $fmt($sum) }} ₽</div>

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

            <div class="px-4 md:px-6 py-4 bg-amber-50/60 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div class="text-slate-700">
                    Всего товаров: {{ collect($cart)->sum('quantity') }}
                </div>

                <div class="text-2xl font-bold text-slate-900">
                    Итого: {{ $fmt($grand) }} ₽
                </div>
            </div>
        </div>

        <div class="mt-6 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('catalog') }}"
               class="btn-ghost text-center">
                Продолжить покупки
            </a>

            <a href="{{ route('checkout') }}"
               class="btn-primary text-center sm:ml-auto">
                Оформить заказ
            </a>
        </div>
    @endif
</div>
@endsection
