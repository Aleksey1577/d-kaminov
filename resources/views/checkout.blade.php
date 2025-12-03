@extends('layouts.app')

@section('title', 'Оформление заказа')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Оформление заказа</h1>

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Форма оформления заказа -->
    <form action="{{ route('checkout') }}" method="POST"
          x-data="{ 
              pickupType: 'pickup',
              paymentMethod: 'cash',
              setPickupType(type) { this.pickupType = type },
              setPaymentMethod(method) { this.paymentMethod = method }
          }"
          class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @csrf

        <!-- Левая часть — форма -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Контактные данные</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Имя -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Имя</label>
                        <input type="text" name="name" id="name" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>

                    <!-- Телефон -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Телефон</label>
                        <input type="text" name="phone" id="phone" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>

                    <!-- Email -->
                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>

                    <!-- Способ получения -->
                    <div class="md:col-span-2">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Способ получения</h3>
                        <div class="flex flex-wrap gap-4 mb-4">
                            <button type="button"
                                    @click="setPickupType('pickup')"
                                    :class="{
                                        'border-orange text-orange': pickupType === 'pickup',
                                        'border-gray-300 text-gray-700': pickupType !== 'pickup'
                                    }"
                                    class="px-4 py-2 border-2 rounded-md transition-colors focus:outline-none">
                                Самовывоз
                            </button>

                            <button type="button"
                                    @click="setPickupType('delivery')"
                                    :class="{
                                        'border-orange text-orange': pickupType === 'delivery',
                                        'border-gray-300 text-gray-700': pickupType !== 'delivery'
                                    }"
                                    class="px-4 py-2 border-2 rounded-md transition-colors focus:outline-none">
                                Адрес доставки
                            </button>
                        </div>

                        <!-- Блок самовывоза -->
                        <div x-show="pickupType === 'pickup'" class="bg-gray-50 p-4 rounded-md mb-4">
                            <p class="font-medium">Наш магазин:</p>
                            <p class="text-gray-600">ТЦ Интермебель, Московское шоссе 16 км, 1в ст2, 2 этаж</p>
                            <p class="text-gray-600">Тел: +7 (917) 953-58-50</p>
                        </div>

                        <!-- Поле адреса доставки -->
                        <div x-show="pickupType === 'delivery'">
                            <label for="address" class="block text-sm font-medium text-gray-700">Адрес доставки</label>
                            <textarea name="address" id="address" rows="3"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"></textarea>
                        </div>
                    </div>

                    <!-- Способ оплаты -->
                    <div class="md:col-span-2">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Способ оплаты</h3>
                        <div class="flex flex-wrap gap-4 mb-4">
                            <button type="button"
                                    @click="setPaymentMethod('cash')"
                                    :class="{
                                        'border-orange text-orange': paymentMethod === 'cash',
                                        'border-gray-300 text-gray-700': paymentMethod !== 'cash'
                                    }"
                                    class="px-4 py-2 border-2 rounded-md transition-colors focus:outline-none">
                                Наличными
                            </button>

                            <button type="button"
                                    @click="setPaymentMethod('card')"
                                    :class="{
                                        'border-orange text-orange': paymentMethod === 'card',
                                        'border-gray-300 text-gray-700': paymentMethod !== 'card'
                                    }"
                                    class="px-4 py-2 border-2 rounded-md transition-colors focus:outline-none">
                                Картой
                            </button>
                        </div>

                        <!-- Скрытые поля для отправки -->
                        <input type="hidden" name="pickup_type" x-model="pickupType">
                        <input type="hidden" name="payment_method" x-model="paymentMethod">
                    </div>
                </div>
            </div>
        </div>

        <!-- Правая часть — корзина -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Ваш заказ</h2>
            <div class="space-y-4">
                @foreach ($cart as $item)
                    <div class="flex justify-between">
                        <span>{{ $item['naimenovanie'] }}</span>
                        <span>{{ number_format($item['price'], 2) }} ₽ x {{ $item['quantity'] }}</span>
                    </div>
                @endforeach
            </div>
            <hr class="my-4">
            <div class="flex justify-between font-bold text-lg">
                <span>Итого:</span>
                <span>
                    {{ number_format(collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']), 2) }} ₽
                </span>
            </div>
            <button type="submit" class="mt-6 w-full bg-orange hover:bg-blue-700 text-white py-2 px-4 rounded">
                Оформить заказ
            </button>
        </div>
    </form>
@endsection

@push('scripts')
<script>
// Alpine.js уже подключен через Vite или CDN
</script>
@endpush