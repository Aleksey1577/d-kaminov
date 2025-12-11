@extends('layouts.admin')

@section('title', 'Заказы')

@section('content')
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
            <div>
                <h1 class="text-2xl font-bold">Заказы</h1>
                <p class="text-sm text-gray-500">Список заказов с быстрым фильтром по статусу</p>
            </div>

            <!-- Фильтр по статусу -->
            <form method="GET" action="{{ route('admin.orders') }}" class="flex items-center gap-2">
                <label for="status" class="text-sm text-gray-600">Статус:</label>
                <select name="status" id="status" onchange="this.form.submit()" class="border rounded-md px-3 py-2 text-sm">
                    <option value="">Все</option>
                    <option value="pending" @selected($request->status == 'pending')>Ожидает оплаты</option>
                    <option value="processing" @selected($request->status == 'processing')>В обработке</option>
                    <option value="shipped" @selected($request->status == 'shipped')>Отправлен</option>
                    <option value="completed" @selected($request->status == 'completed')>Выполнен</option>
                    <option value="cancelled" @selected($request->status == 'cancelled')>Отменён</option>
                </select>
                @if($request->status)
                    <a href="{{ route('admin.orders') }}" class="text-sm text-gray-500 hover:text-gray-700">Сбросить</a>
                @endif
            </form>
        </div>

        @if($orders->isEmpty())
            <p class="text-gray-600">Нет заказов.</p>
        @else
            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full border-collapse text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="border-b p-3 text-left font-semibold">ID</th>
                            <th class="border-b p-3 text-left font-semibold">Клиент</th>
                            <th class="border-b p-3 text-left font-semibold">Сумма</th>
                            <th class="border-b p-3 text-left font-semibold">Статус</th>
                            <th class="border-b p-3 text-left font-semibold">Дата</th>
                            <th class="border-b p-3 text-left font-semibold w-28">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="border-t p-3 align-middle">#{{ $order->id }}</td>
                                <td class="border-t p-3 align-middle">{{ $order->name }}</td>
                                <td class="border-t p-3 align-middle">{{ number_format($order->total, 2) }} ₽</td>
                                <td class="border-t p-3 align-middle">
                                    <span class="inline-flex px-2 py-1 rounded text-xs font-semibold bg-gray-100 text-gray-700">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="border-t p-3 align-middle">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                <td class="border-t p-3 align-middle">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:underline">Открыть</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection
