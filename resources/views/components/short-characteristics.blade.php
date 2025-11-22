{{-- resources/views/components/short-characteristics.blade.php --}}

<div class="mb-6">
    <h2 class="text-xl font-semibold mb-4">Характеристики</h2>
    <div class="space-y-3">
        @if($product->sku)
            <div class="flex justify-between items-center">
                <span class="font-medium text-gray-700 min-w-fit">Артикул:</span>
                <span class="text-gray-600 text-right flex-1">{{ $product->sku }}</span>
            </div>
        @endif
        @if($product->proizvoditel)
            <div class="flex justify-between items-center">
                <span class="font-medium text-gray-700 min-w-fit">Производитель:</span>
                <span class="text-gray-600 text-right flex-1">{{ $product->proizvoditel }}</span>
            </div>
        @endif
        @if($product->kategoriya)
            <div class="flex justify-between items-center">
                <span class="font-medium text-gray-700 min-w-fit">Категория:</span>
                <span class="text-gray-600 text-right flex-1">{{ $product->kategoriya }}</span>
            </div>
        @endif
        @if($product->material)
            <div class="flex justify-between items-center">
                <span class="font-medium text-gray-700 min-w-fit">Материал:</span>
                <span class="text-gray-600 text-right flex-1">{{ $product->material }}</span>
            </div>
        @endif
    </div>

    <!-- Кнопка для просмотра всех характеристик -->
    <button class="mt-4 text-blue-600 hover:underline">
        Смотреть все характеристики
    </button>
</div>