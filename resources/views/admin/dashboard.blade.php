@extends('layouts.admin')

@section('title', 'Главная админки')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-semibold">Всего заказов</h2>
            <p class="text-2xl font-bold text-orange mt-2">{{ $totalOrders }}</p>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-semibold">В обработке</h2>
            <p class="text-2xl font-bold text-yellow-500 mt-2">{{ $pendingOrders }}</p>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-semibold">Последний заказ</h2>
            <p class="text-2xl font-bold text-blue-600 mt-2">#{{ $lastOrder?->id ?? '–' }}</p>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-semibold">Статус последнего заказа</h2>
            <p class="text-2xl font-bold text-purple-600 mt-2">
                {{ $lastOrder?->status ? ucfirst($lastOrder->status) : '–' }}
            </p>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6 flex justify-between items-center">
            <h2 class="text-xl font-bold">Последние заказы</h2>
            <a href="{{ route('admin.orders') }}" class="text-orange hover:underline">Посмотреть все</a>
        </div>
        <div class="p-6 overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border p-3 text-left">ID</th>
                        <th class="border p-3 text-left">Клиент</th>
                        <th class="border p-3 text-left">Сумма</th>
                        <th class="border p-3 text-left">Статус</th>
                        <th class="border p-3 text-left">Дата</th>
                        <th class="border p-3 text-left">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="border p-3">#{{ $order->id }}</td>
                            <td class="border p-3">{{ $order->name }}</td>
                            <td class="border p-3">{{ number_format($order->total, 2) }} ₽</td>
                            <td class="border p-3">{{ ucfirst($order->status) }}</td>
                            <td class="border p-3">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                            <td class="border p-3">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:underline">Посмотреть</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center p-4">Нет данных</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection