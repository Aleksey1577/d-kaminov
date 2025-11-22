@extends('layouts.admin') 

@section('title', 'Управление категориями')

@section('content')
    <div class="container mx-auto px-4 py-8 flex">


        <!-- Основной контент -->
        <main class="flex-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Управление категориями</h1>
                    <a href="{{ route('admin.categories.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                        Добавить категорию
                    </a>
                </div>

                @if ($categories->isEmpty())
                    <p class="text-gray-700">Категории отсутствуют.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="border p-3 text-left">ID</th>
                                    <th class="border p-3 text-left">Название</th>
                                    <th class="border p-3 text-left">Слаг</th>
                                    <th class="border p-3 text-left">Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border p-3">{{ $category->id }}</td>
                                        <td class="border p-3">{{ $category->name }}</td>
                                        <td class="border p-3">{{ $category->slug }}</td>
                                        <td class="border p-3 space-x-2">
                                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600 hover:underline">
                                                Редактировать
                                            </a>
                                            <form action="{{ route('admin.categories.destroy', $category) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Вы уверены, что хотите удалить эту категорию?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline">
                                                    Удалить
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $categories->links() }}
                @endif
            </div>
        </main>
    </div>
@endsection