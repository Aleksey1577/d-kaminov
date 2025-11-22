{{-- resources/views/profile.blade.php --}}
@extends('layouts.app')

@section('title', 'Личный кабинет')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6">Личный кабинет</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-6">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Основная информация -->
        <div class="md:col-span-1 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Профиль</h2>
            <p><strong>Имя:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Дата регистрации:</strong> {{ $user->created_at->format('d.m.Y H:i') }}</p>

            <!-- Кнопка редактирования профиля -->
            <a href="#edit-profile" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Редактировать профиль
            </a>

            <!-- Кнопка выхода -->
            <form action="{{ route('logout') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    Выйти
                </button>
            </form>
        </div>

        <!-- Избранное -->
        <div class="md:col-span-1 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Избранное</h2>
            <p>Количество товаров: {{ $favoritesCount ?? count(session('favorites', [])) }}</p>
            <a href="{{ route('favorites') }}" class="mt-2 inline-block text-blue-600 hover:underline">
                Перейти в избранное
            </a>
        </div>

        <!-- Сравнение -->
        <div class="md:col-span-1 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Сравнение</h2>
            <p>Количество товаров: {{ $compareCount ?? count(session('compare', [])) }}</p>
            <a href="{{ route('compare') }}" class="mt-2 inline-block text-blue-600 hover:underline">
                Перейти в сравнение
            </a>
        </div>
    </div>

    <!-- Заказы (если есть) -->
    <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Мои заказы</h2>
        @if ($orders ?? collect()->isEmpty())
            <p>У вас пока нет заказов.</p>
        @else
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-3 text-left">Номер заказа</th>
                        <th class="p-3 text-left">Дата</th>
                        <th class="p-3 text-left">Статус</th>
                        <th class="p-3 text-left">Сумма</th>
                        <th class="p-3 text-left">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders ?? [] as $order)
                        <tr>
                            <td class="p-3">{{ $order->id }}</td>
                            <td class="p-3">{{ $order->created_at->format('d.m.Y') }}</td>
                            <td class="p-3">{{ $order->status }}</td>
                            <td class="p-3">{{ number_format($order->total ?? 0, 0, '', '') }} ₽</td>
                            <td class="p-3">
                                <a href="{{ route('order.show', $order) }}" class="text-blue-600 hover:underline">Подробнее</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Форма редактирования профиля (модалка или секция) -->
    <div id="edit-profile" class="mt-8 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Редактировать профиль</h2>
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Имя</label>
                <input type="text" name="name" id="name" class="w-full border rounded px-3 py-2" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="w-full border rounded px-3 py-2" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Сохранить изменения
            </button>
        </form>
    </div>
</div>
@endsection