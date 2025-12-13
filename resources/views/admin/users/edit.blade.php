@extends('layouts.admin')

@section('title', 'Редактирование пользователя')

@section('content')
    <div class="bg-white shadow-md rounded-lg p-6 max-w-2xl">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold">Пользователь #{{ $user->id }}</h1>
                <p class="text-sm text-gray-500">{{ $user->name }} · {{ $user->email }}</p>
            </div>

            <a href="{{ route('admin.users') }}" class="text-sm text-gray-600 hover:text-orange whitespace-nowrap">
                ← Назад
            </a>
        </div>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="space-y-2">
                <label class="flex items-center gap-3">
                    <input
                        type="checkbox"
                        name="is_admin"
                        value="1"
                        class="rounded border-gray-300"
                        @checked((bool) old('is_admin', $user->is_admin))
                    >
                    <span class="text-sm font-medium text-gray-800">Администратор</span>
                </label>
                @error('is_admin')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-orange text-white px-4 py-2 rounded hover:bg-orange-white">
                    Сохранить
                </button>
            </div>
        </form>

        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="mt-4" onsubmit="return confirm('Удалить пользователя?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:underline">Удалить пользователя</button>
        </form>
    </div>
@endsection
