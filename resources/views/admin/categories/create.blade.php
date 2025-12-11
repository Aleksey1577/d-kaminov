@extends('layouts.admin')

@section('title', 'Добавить категорию')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold">Добавить категорию</h1>
                <p class="text-sm text-gray-500">Создайте новую категорию каталога</p>
            </div>
            <a href="{{ route('admin.categories') }}"
               class="text-sm px-3 py-2 rounded-md border border-gray-200 hover:bg-gray-50">← Назад</a>
        </div>

        @include('admin.categories._form')
    </div>
@endsection
