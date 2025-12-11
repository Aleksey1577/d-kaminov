@extends('layouts.admin')

@section('title', 'Товары')

@section('content')
<div class="bg-white shadow-sm border border-gray-200 rounded-2xl p-6 space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-sm text-gray-500">Управление каталогом</p>
            <h1 class="text-2xl font-semibold text-gray-900">Товары</h1>
        </div>
        <a href="{{ route('admin.products.create') }}"
           class="inline-flex items-center gap-2 bg-orange text-white px-4 py-2.5 rounded-lg shadow hover:bg-orange-500 transition">
            <span class="text-lg leading-none">＋</span>
            <span>Добавить товар</span>
        </a>
    </div>

    <!-- Фильтры -->
    <form method="GET" action="{{ route('admin.products') }}" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            {{-- Поиск --}}
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-600 mb-1">Поиск</label>
                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Название или артикул…"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 focus:border-orange-500 focus:ring focus:ring-orange-200">
            </div>

            {{-- Категория --}}
            <div>
                <label class="block text-sm text-gray-600 mb-1">Категория</label>
                <select name="kategoriya" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 focus:border-orange-500 focus:ring focus:ring-orange-200">
                    <option value="">Все</option>
                    @foreach ($categories as $opt)
                    <option value="{{ $opt }}" @selected(request('kategoriya')===$opt)>
                        {{ $opt }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Поставщик --}}
            <div>
                <label class="block text-sm text-gray-600 mb-1">Поставщик</label>
                <select name="postavshik" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 focus:border-orange-500 focus:ring focus:ring-orange-200">
                    <option value="">Все</option>
                    @foreach ($suppliers as $opt)
                    <option value="{{ $opt }}" @selected(request('postavshik')===$opt)>
                        {{ $opt }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Производитель --}}
            <div>
                <label class="block text-sm text-gray-600 mb-1">Производитель</label>
                <select name="proizvoditel" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 focus:border-orange-500 focus:ring focus:ring-orange-200">
                    <option value="">Все</option>
                    @foreach ($manufacturers as $opt)
                    <option value="{{ $opt }}" @selected(request('proizvoditel')===$opt)>
                        {{ $opt }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            {{-- Цена от --}}
            <div>
                <label class="block text-sm text-gray-600 mb-1">Цена от</label>
                <input type="number"
                    name="price_min"
                    value="{{ request('price_min') }}"
                    step="0.01"
                    min="0"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 focus:border-orange-500 focus:ring focus:ring-orange-200">
            </div>

            {{-- Цена до --}}
            <div>
                <label class="block text-sm text-gray-600 mb-1">Цена до</label>
                <input type="number"
                    name="price_max"
                    value="{{ request('price_max') }}"
                    step="0.01"
                    min="0"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 focus:border-orange-500 focus:ring focus:ring-orange-200">
            </div>

            <div class="md:col-span-3 flex items-end gap-3">
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 bg-orange text-white px-4 py-2.5 rounded-lg shadow hover:bg-orange-500 transition">
                    Применить
                </button>

                <a href="{{ route('admin.products') }}"
                    class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                    Сбросить
                </a>
            </div>
        </div>
    </form>

    @if ($products->isEmpty())
    <p class="text-gray-600">Нет товаров</p>
    @else
    <div class="overflow-x-auto border border-gray-200 rounded-xl">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-600 uppercase text-xs tracking-wide">
                <tr>
                    <th class="px-4 py-3 w-20">Фото</th>
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">SKU</th>
                    <th class="px-4 py-3">Название</th>
                    <th class="px-4 py-3">Цена</th>
                    <th class="px-4 py-3">Категория</th>
                    <th class="px-4 py-3 text-right">Действия</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($products as $product)
                <tr class="hover:bg-gray-50">
                    {{-- Фото товара --}}
                    <td class="px-4 py-3">
                        <div class="h-14 w-14 rounded-lg bg-gray-100 overflow-hidden">
                            <img
                                src="{{ $product->thumb_url }}"
                                alt="{{ $product->naimenovanie }}"
                                width="56" height="56" {{-- резервируем место, снижает CLS --}}
                                loading="lazy" decoding="async" {{-- быстрее/ленивее --}}
                                class="block h-14 w-14 object-cover">
                        </div>
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $product->product_id }}</td>
                    <td class="px-4 py-3 text-gray-700">{{ $product->sku }}</td>
                    <td class="px-4 py-3 text-gray-900">{{ $product->naimenovanie }}</td>
                    <td class="px-4 py-3 text-gray-900">{{ number_format($product->price, 2, ',', ' ') }} ₽</td>
                    <td class="px-4 py-3 text-gray-700">{{ $product->kategoriya }}</td>

                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.products.edit', $product) }}"
                            class="text-orange font-semibold hover:underline">Редактировать</a>

                        <form action="{{ route('admin.products.destroy', $product) }}"
                            method="POST"
                            class="inline"
                            onsubmit="return confirm('Удалить товар?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-red-600 hover:underline">
                                Удалить
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
