@extends('layouts.admin')

@section('title', 'Товары')

@section('content')
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between mb-6">
            <h1 class="text-2xl font-bold">Товары</h1>
            <a href="{{ route('admin.products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">Добавить товар</a>
        </div>

        <!-- Поиск -->
        <form method="GET" action="{{ route('admin.products') }}" class="mb-6">
            <div class="flex space-x-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Поиск по названию..." class="w-full border rounded px-3 py-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">Найти</button>
            </div>
        </form>

        @if ($products->isEmpty())
            <p class="text-gray-600">Нет товаров</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border p-3 text-left">ID</th>
                            <th class="border p-3 text-left">Название</th>
                            <th class="border p-3 text-left">Цена</th>
                            <th class="border p-3 text-left">Категория</th>
                            <th class="border p-3 text-left">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr class="hover:bg-gray-50">
                                <td class="border p-3">{{ $product->id }}</td>
                                <td class="border p-3">{{ $product->naimenovanie }}</td>
                                <td class="border p-3">{{ number_format($product->price, 2) }} ₽</td>
                                <td class="border p-3">{{ $product->kategoriya }}</td>
                                <td class="border p-3 space-x-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:underline">Редактировать</a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Удалить товар?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Удалить</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $products->links() }}
        @endif
    </div>
@endsection