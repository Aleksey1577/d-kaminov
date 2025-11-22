@extends('layouts.admin')

@section('title', 'Добавить товар')

@section('content')
    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Добавить товар</h1>

        <form action="{{ route('admin.products.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="naimenovanie" class="block text-gray-700 mb-2">Название</label>
                    <input type="text" name="naimenovanie" id="naimenovanie" required class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="price" class="block text-gray-700 mb-2">Цена</label>
                    <input type="number" name="price" id="price" required min="0" step="0.01" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="kategoriya" class="block text-gray-700 mb-2">Категория</label>
                    <input type="text" name="kategoriya" id="kategoriya" required class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="v_nalichii_na_sklade" class="block text-gray-700 mb-2">В наличии</label>
                    <select name="v_nalichii_na_sklade" id="v_nalichii_na_sklade" class="w-full border rounded p-2">
                        <option value="Да">Да</option>
                        <option value="Нет">Нет</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label for="opisanije" class="block text-gray-700 mb-2">Описание</label>
                    <textarea name="opisanije" id="opisanije" class="w-full border rounded p-2"></textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="sku" class="block text-gray-700 mb-2">Артикул</label>
                    <input type="text" name="sku" id="sku" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="proizvoditel" class="block text-gray-700 mb-2">Производитель</label>
                    <input type="text" name="proizvoditel" id="proizvoditel" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="price2" class="block text-gray-700 mb-2">Цена 2</label>
                    <input type="number" name="price2" id="price2" min="0" step="0.01" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="material" class="block text-gray-700 mb-2">Материал</label>
                    <input type="text" name="material" id="material" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="vysota" class="block text-gray-700 mb-2">Высота</label>
                    <input type="text" name="vysota" id="vysota" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="shirina" class="block text-gray-700 mb-2">Ширина</label>
                    <input type="text" name="shirina" id="shirina" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="glubina" class="block text-gray-700 mb-2">Глубина</label>
                    <input type="text" name="glubina" id="glubina" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="ves" class="block text-gray-700 mb-2">Вес</label>
                    <input type="text" name="ves" id="ves" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="tsvet" class="block text-gray-700 mb-2">Цвет</label>
                    <input type="text" name="tsvet" id="tsvet" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="garantiya" class="block text-gray-700 mb-2">Гарантия</label>
                    <input type="text" name="garantiya" id="garantiya" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="image_url" class="block text-gray-700 mb-2">Фото (URL)</label>
                    <input type="url" name="image_url" id="image_url" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="image_url_1" class="block text-gray-700 mb-2">Фото 1 (URL)</label>
                    <input type="url" name="image_url_1" id="image_url_1" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="image_url_2" class="block text-gray-700 mb-2">Фото 2 (URL)</label>
                    <input type="url" name="image_url_2" id="image_url_2" class="w-full border rounded p-2">
                </div>
                <div>
                    <label for="image_url_3" class="block text-gray-700 mb-2">Фото 3 (URL)</label>
                    <input type="url" name="image_url_3" id="image_url_3" class="w-full border rounded p-2">
                </div>
            </div>

            <button type="submit" class="mt-4 bg-orange text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                Добавить товар
            </button>
        </form>
    </div>
@endsection