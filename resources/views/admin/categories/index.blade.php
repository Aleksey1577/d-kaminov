@extends('layouts.admin') 

@section('title', 'Управление категориями')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
            <div>
                <h1 class="text-2xl font-bold">Категории</h1>
                <p class="text-sm text-gray-500">Управление списком категорий каталога</p>
            </div>
            <a href="{{ route('admin.categories.create') }}"
               class="inline-flex items-center gap-2 bg-orange text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-orange-white transition">
                + Добавить категорию
            </a>
        </div>

        @if ($categories->isEmpty())
            <p class="text-gray-700">Категории отсутствуют.</p>
        @else
            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full border-collapse text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-gray-600">
                            <th class="border-b p-3 font-semibold">ID</th>
                            <th class="border-b p-3 font-semibold">Название</th>
                            <th class="border-b p-3 font-semibold">Слаг</th>
                            <th class="border-b p-3 font-semibold w-40">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr class="hover:bg-gray-50">
                                <td class="border-t p-3 align-middle">#{{ $category->id }}</td>
                                <td class="border-t p-3 align-middle">{{ $category->name }}</td>
                                <td class="border-t p-3 align-middle text-gray-600">{{ $category->slug }}</td>
                                <td class="border-t p-3 align-middle">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600 hover:underline">Редактировать</a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" 
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Удалить категорию?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Удалить</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
@endsection
