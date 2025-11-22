<div x-data="{ tab: 'description' }" class="mt-8">
    <!-- Табы -->
    <div class="flex mb-6 border-b">
        <button :class="tab === 'description' ? 'text-orange border-b-2 border-orange' : 'text-gray-600'"
            @click="tab = 'description'" class="px-4 py-2">Описание</button>
        <button :class="tab === 'characteristics' ? 'text-orange border-b-2 border-orange' : 'text-gray-600'"
            @click="tab = 'characteristics'" class="px-4 py-2">Характеристики</button>
        <button :class="tab === 'delivery' ? 'text-orange border-b-2 border-orange' : 'text-gray-600'"
            @click="tab = 'delivery'" class="px-4 py-2">Доставка</button>
        <button :class="tab === 'payment' ? 'text-orange border-b-2 border-orange' : 'text-gray-600'"
            @click="tab = 'payment'" class="px-4 py-2">Оплата</button>
    </div>

    <!-- Содержимое вкладок -->
    <div x-show="tab === 'description'" class="text-gray-600 prose prose-sm max-w-none">
        {!! $product->opisanije !!}
    </div>

    <div x-show="tab === 'characteristics'" class="mt-4 text-gray-600">
        <div class="grid grid-cols-1 gap-2">
            @foreach($product->getFullCharacteristics() as $key => $value)
            <div class="flex">
                <span class="w-1/3 font-medium text-gray-700">{{ $key }}:</span>
                <span class="text-gray-600">{{ $value }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <div x-show="tab === 'delivery'" class="mt-4 text-gray-600">
        <p>Доставка осуществляется по всей территории страны. Стоимость и сроки доставки зависят от вашего региона.</p>
    </div>

    <div x-show="tab === 'payment'" class="mt-4 text-gray-600">
        <p>Мы принимаем оплату через банковские карты, электронные кошельки и наложенный платеж при получении.</p>
    </div>
</div>