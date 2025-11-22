@extends('layouts.app')

@section('title', 'Редактировать категорию')

@section('content')
    <div class="flex min-h-screen">
        <!-- Боковое меню -->
        <div class="bg-gray-800 text-white w-64 p-6">
            <h2 class="text-xl font-bold mb-6">Админ-панель</h2>
            <nav class="space-y-2">
                <a href="{{ route('admin.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700">Главная</a>
                <a href="{{ route('admin.products') }}" class="block px-4 py-2 rounded hover:bg-gray-700">Товары</a>
                <a href="{{ route('admin.categories') }}" class="block px-4 py-2 rounded bg-gray-700">Категории</a>
            </nav>
        </div>
        <!-- Основной контент -->
        <div class="flex-1 p-6">
            <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto">
                <h1 class="text-2xl font-bold mb-6">Редактировать категорию</h1>
                <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 mb-1">Название</label>
                        <input type="text" name="name" id="name" class="w-full border rounded px-3 py-2" value="{{ old('name', $category->name) }}" required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.categories') }}" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">Отмена</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection