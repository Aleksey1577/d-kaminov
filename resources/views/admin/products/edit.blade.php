{{-- resources/views/admin/products/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Редактировать товар')

@section('content')
    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Редактировать товар</h1>

        <form action="{{ route('admin.products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="naimenovanie" class="block text-gray-700 mb-2">Название</label>
                    <input type="text" name="naimenovanie" id="naimenovanie" value="{{ old('naimenovanie', $product->naimenovanie) }}" required class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="price" class="block text-gray-700 mb-2">Цена</label>
                    <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" required class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="kategoriya" class="block text-gray-700 mb-2">Категория</label>
                    <input type="text" name="kategoriya" id="kategoriya" value="{{ old('kategoriya', $product->kategoriya) }}" required class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="v_nalichii_na_sklade" class="block text-gray-700 mb-2">В наличии</label>
                    <select name="v_nalichii_na_sklade" id="v_nalichii_na_sklade" class="w-full border rounded p-2">
                        <option value="Да" {{ $product->v_nalichii_na_sklade === 'Да' ? 'selected' : '' }}>Да</option>
                        <option value="Нет" {{ $product->v_nalichii_na_sklade === 'Нет' ? 'selected' : '' }}>Нет</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label for="opisanije" class="block text-gray-700 mb-2">Описание</label>
                    <textarea name="opisanije" id="opisanije" class="w-full border rounded p-2">{{ old('opisanije', $product->opisanije) }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="sku" class="block text-gray-700 mb-2">Артикул</label>
                    <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="proizvoditel" class="block text-gray-700 mb-2">Производитель</label>
                    <input type="text" name="proizvoditel" id="proizvoditel" value="{{ old('proizvoditel', $product->proizvoditel) }}" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="price2" class="block text-gray-700 mb-2">Цена 2</label>
                    <input type="number" name="price2" id="price2" value="{{ old('price2', $product->price2) }}" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="material" class="block text-gray-700 mb-2">Материал</label>
                    <input type="text" name="material" id="material" value="{{ old('material', $product->material) }}" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="vysota" class="block text-gray-700 mb-2">Высота</label>
                    <input type="text" name="vysota" id="vysota" value="{{ old('vysota', $product->vysota) }}" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="shirina" class="block text-gray-700 mb-2">Ширина</label>
                    <input type="text" name="shirina" id="shirina" value="{{ old('shirina', $product->shirina) }}" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="glubina" class="block text-gray-700 mb-2">Глубина</label>
                    <input type="text" name="glubina" id="glubina" value="{{ old('glubina', $product->glubina) }}" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="ves" class="block text-gray-700 mb-2">Вес</label>
                    <input type="text" name="ves" id="ves" value="{{ old('ves', $product->ves) }}" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="tsvet" class="block text-gray-700 mb-2">Цвет</label>
                    <input type="text" name="tsvet" id="tsvet" value="{{ old('tsvet', $product->tsvet) }}" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="garantiya" class="block text-gray-700 mb-2">Гарантия</label>
                    <input type="text" name="garantiya" id="garantiya" value="{{ old('garantiya', $product->garantiya) }}" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="image_url" class="block text-gray-700 mb-2">Фото (URL)</label>
                    <input type="url" name="image_url" id="image_url" value="{{ old('image_url', $product->image_url) }}" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="image_url_1" class="block text-gray-700 mb-2">Фото 1 (URL)</label>
                    <input type="url" name="image_url_1" id="image_url_1" value="{{ old('image_url_1', $product->image_url_1) }}" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="image_url_2" class="block text-gray-700 mb-2">Фото 2 (URL)</label>
                    <input type="url" name="image_url_2" id="image_url_2" value="{{ old('image_url_2', $product->image_url_2) }}" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="image_url_3" class="block text-gray-700 mb-2">Фото 3 (URL)</label>
                    <input type="url" name="image_url_3" id="image_url_3" value="{{ old('image_url_3', $product->image_url_3) }}" class="w-full border rounded p-2">
                </div>
            </div>

            <div class="flex space-x-4">
                <a href="{{ route('admin.products') . request()->getQueryString() }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition-colors">
                    Назад
                </a>
                <button type="submit" class="bg-orange text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                    Обновить товар
                </button>
            </div>
        </form>
    </div>
@endsection