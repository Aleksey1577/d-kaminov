@extends('layouts.admin')

@section('title', 'Главная админки')

@section('content')

    <div class="mb-2">
        <span class="text-sm text-gray-500 mr-1">Сайт:</span>
        <a href="{{ url('/') }}" target="_blank"
           class="text-orange font-semibold hover:underline">
            {{ config('app.name', 'Перейти на сайт') }}
        </a>
    </div>

    <h1 class="text-2xl font-bold mb-6">Добро пожаловать, {{ auth()->user()->name }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-semibold">Заказы</h2>
            <p class="text-3xl font-bold text-orange mt-2">{{ $totalOrders }}</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-semibold">Товары</h2>
            <p class="text-3xl font-bold text-orange mt-2">{{ $totalProducts }}</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-semibold">Активные пользователи (за сутки)</h2>
            <p class="text-3xl font-bold text-orange mt-2">{{ $activeUsers }}</p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-semibold">Новые пользователи (7 дней)</h2>
            <p class="text-3xl font-bold text-orange mt-2">{{ $newUsers }}</p>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 mt-6">
        <h2 class="text-xl font-bold mb-4">Последние заказы</h2>

        @if($orders->isEmpty())
            <p class="text-gray-600">Нет недавних заказов</p>
        @else
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-3 text-left">ID</th>
                        <th class="border p-3 text-left">Клиент</th>
                        <th class="border p-3 text-left">Сумма</th>
                        <th class="border p-3 text-left">Дата</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="border p-3">#{{ $order->id }}</td>
                            <td class="border p-3">{{ $order->name }}</td>
                            <td class="border p-3">{{ number_format($order->total, 2) }} ₽</td>
                            <td class="border p-3">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
