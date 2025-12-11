@extends('layouts.admin')

@section('title', 'Заказ #' . $order->id)

@section('content')
    <div class="bg-white shadow-md rounded-lg p-6 space-y-6">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h1 class="text-2xl font-bold">Заказ #{{ $order->id }}</h1>
                <p class="text-sm text-gray-500">Создан {{ $order->created_at->format('d.m.Y H:i') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                    Статус: {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="border rounded-lg p-4">
                <h2 class="font-semibold text-lg mb-3">Клиент</h2>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-600">Имя</dt><dd class="font-medium">{{ $order->name }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-600">Email</dt><dd class="font-medium">{{ $order->email }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-600">Телефон</dt><dd class="font-medium">{{ $order->phone }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-600">Получение</dt><dd class="font-medium">{{ $order->pickup_type === 'pickup' ? 'Самовывоз' : 'Доставка' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-600">Адрес</dt><dd class="font-medium text-right">{{ $order->address ?? '—' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-600">Оплата</dt><dd class="font-medium">{{ $order->payment_method === 'cash' ? 'Наличными' : 'Картой' }}</dd></div>
                </dl>
            </div>
            <div class="border rounded-lg p-4">
                <h2 class="font-semibold text-lg mb-3">Детали заказа</h2>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-600">Сумма</dt><dd class="font-medium">{{ number_format($order->total, 2) }} ₽</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-600">Создан</dt><dd class="font-medium">{{ $order->created_at->format('d.m.Y H:i') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-600">Обновлён</dt><dd class="font-medium">{{ $order->updated_at->format('d.m.Y H:i') }}</dd></div>
                </dl>
            </div>
        </div>

        <div class="border rounded-lg overflow-hidden">
            <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b">
                <h2 class="text-lg font-semibold">Товары</h2>
                <span class="text-sm text-gray-500">Всего: {{ number_format($order->total, 2) }} ₽</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="border-b p-3 text-left font-semibold">Название</th>
                            <th class="border-b p-3 text-left font-semibold">Цена</th>
                            <th class="border-b p-3 text-left font-semibold">Кол-во</th>
                            <th class="border-b p-3 text-left font-semibold">Итого</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="border-t p-3">{{ $item->name }}</td>
                                <td class="border-t p-3">{{ number_format($item->price, 2) }} ₽</td>
                                <td class="border-t p-3">{{ $item->quantity }}</td>
                                <td class="border-t p-3 font-semibold">{{ number_format($item->total, 2) }} ₽</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <form action="{{ route('admin.orders.update.status', $order) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="completed">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Выполнен
                </button>
            </form>

            <form action="{{ route('admin.orders.update.status', $order) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="cancelled">
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Отменить
                </button>
            </form>
        </div>
    </div>
@endsection
