@extends('layouts.admin')

@section('title', 'Заказы')

@section('content')
    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Заказы</h1>

        <!-- Фильтр по статусу -->
        <form method="GET" action="{{ route('admin.orders') }}" class="mb-6">
            <select name="status" onchange="this.form.submit()" class="border rounded px-3 py-2">
                <option value="">Все статусы</option>
                <option value="pending" @selected($request->status == 'pending')>Ожидает оплаты</option>
                <option value="processing" @selected($request->status == 'processing')>В обработке</option>
                <option value="shipped" @selected($request->status == 'shipped')>Отправлен</option>
                <option value="completed" @selected($request->status == 'completed')>Выполнен</option>
                <option value="cancelled" @selected($request->status == 'cancelled')>Отменён</option>
            </select>
        </form>

        @if($orders->isEmpty())
            <p class="text-gray-600">Нет заказов.</p>
        @else
            <div class="overflow-x-auto">
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
                        @foreach ($orders as $order)
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
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $orders->links() }}
        @endif
    </div>
@endsection