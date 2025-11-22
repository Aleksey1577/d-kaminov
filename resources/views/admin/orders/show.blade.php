@extends('layouts.admin')

@section('title', 'Заказ #' . $order->id)

@section('content')
    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Заказ #{{ $order->id }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h2 class="font-semibold text-lg mb-4">Информация о клиенте</h2>
                <p><strong>Имя:</strong> {{ $order->name }}</p>
                <p><strong>Email:</strong> {{ $order->email }}</p>
                <p><strong>Телефон:</strong> {{ $order->phone }}</p>
                <p><strong>Способ получения:</strong>
                    {{ $order->pickup_type === 'pickup' ? 'Самовывоз' : 'Доставка' }}
                </p>
                <p><strong>Адрес:</strong> {{ $order->address ?? '—' }}</p>
                <p><strong>Оплата:</strong>
                    {{ $order->payment_method === 'cash' ? 'Наличными' : 'Картой' }}
                </p>
            </div>
            <div>
                <h2 class="font-semibold text-lg mb-4">Информация о заказе</h2>
                <p><strong>Статус:</strong> {{ ucfirst($order->status) }}</p>
                <p><strong>Общая сумма:</strong> {{ number_format($order->total, 2) }} ₽</p>
                <p><strong>Дата создания:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>
                <p><strong>Дата обновления:</strong> {{ $order->updated_at->format('d.m.Y H:i') }}</p>
            </div>
        </div>

        <h2 class="text-xl font-semibold mb-4">Товары в заказе</h2>
        <div class="overflow-x-auto mb-6">
            <table class="min-w-full border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border p-3 text-left">Название</th>
                        <th class="border p-3 text-left">Цена</th>
                        <th class="border p-3 text-left">Количество</th>
                        <th class="border p-3 text-left">Итого</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="border p-3">{{ $item->name }}</td>
                            <td class="border p-3">{{ number_format($item->price, 2) }} ₽</td>
                            <td class="border p-3">{{ $item->quantity }}</td>
                            <td class="border p-3">{{ number_format($item->total, 2) }} ₽</td>
                        </tr>
                    @endforeach
                    <tr class="bg-gray-100">
                        <td colspan="3" class="border p-3 text-right font-bold">Общая сумма:</td>
                        <td class="border p-3 font-bold">{{ number_format($order->total, 2) }} ₽</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex space-x-4">
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