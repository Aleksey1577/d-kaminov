@extends('layouts.admin')

@section('title', 'Товары')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">
    <div class="flex justify-between mb-6">
        <h1 class="text-2xl font-bold">Товары</h1>
        <a href="{{ route('admin.products.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
            Добавить товар
        </a>
    </div>

    <!-- Фильтры -->
    <form method="GET" action="{{ route('admin.products') }}" class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            {{-- Поиск --}}
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-600 mb-1">Поиск</label>
                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Название или артикул…"
                    class="w-full border rounded px-3 py-2">
            </div>

            {{-- Категория --}}
            <div>
                <label class="block text-sm text-gray-600 mb-1">Категория</label>
                <select name="kategoriya" class="w-full border rounded px-3 py-2">
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
                <select name="postavshik" class="w-full border rounded px-3 py-2">
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
                <select name="proizvoditel" class="w-full border rounded px-3 py-2">
                    <option value="">Все</option>
                    @foreach ($manufacturers as $opt)
                    <option value="{{ $opt }}" @selected(request('proizvoditel')===$opt)>
                        {{ $opt }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mt-4">
            {{-- Цена от --}}
            <div>
                <label class="block text-sm text-gray-600 mb-1">Цена от</label>
                <input type="number"
                    name="price_min"
                    value="{{ request('price_min') }}"
                    step="0.01"
                    min="0"
                    class="w-full border rounded px-3 py-2">
            </div>

            {{-- Цена до --}}
            <div>
                <label class="block text-sm text-gray-600 mb-1">Цена до</label>
                <input type="number"
                    name="price_max"
                    value="{{ request('price_max') }}"
                    step="0.01"
                    min="0"
                    class="w-full border rounded px-3 py-2">
            </div>

            <div class="md:col-span-3 flex items-end gap-3">
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                    Применить
                </button>

                <a href="{{ route('admin.products') }}"
                    class="px-4 py-2 rounded border hover:bg-gray-50 transition-colors">
                    Сбросить
                </a>
            </div>
        </div>
    </form>

    @if ($products->isEmpty())
    <p class="text-gray-600">Нет товаров</p>
    @else
    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-3 text-left w-20">Фото</th>
                    <th class="border p-3 text-left">ID</th>
                    <th class="border p-3 text-left">SKU</th>
                    <th class="border p-3 text-left">Название</th>
                    <th class="border p-3 text-left">Цена</th>
                    <th class="border p-3 text-left">Категория</th>
                    <th class="border p-3 text-left">Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr class="hover:bg-gray-50">
                    {{-- Фото товара --}}
                    <td class="border p-3">
                        <div class="h-14 w-14 rounded bg-gray-100 overflow-hidden">
                            <img
                                src="{{ $product->thumb_url }}"
                                alt="{{ $product->naimenovanie }}"
                                width="56" height="56" {{-- резервируем место, снижает CLS --}}
                                loading="lazy" decoding="async" {{-- быстрее/ленивее --}}
                                class="block h-14 w-14 object-cover">
                        </div>
                    </td>
                    <td class="border p-3">{{ $product->product_id }}</td>
                    <td class="border p-3">{{ $product->sku }}</td>
                    <td class="border p-3">{{ $product->naimenovanie }}</td>
                    <td class="border p-3">{{ number_format($product->price, 2, ',', ' ') }} ₽</td>
                    <td class="border p-3">{{ $product->kategoriya }}</td>

                    <td class="border p-3 space-x-2">
                        <a href="{{ route('admin.products.edit', $product) }}"
                            class="text-blue-600 hover:underline">Редактировать</a>

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