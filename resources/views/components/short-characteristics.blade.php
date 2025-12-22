<div class="surface-quiet p-4 sm:p-5 mb-6">
    <h2 class="text-lg sm:text-xl font-semibold text-slate-900 mb-3 sm:mb-4">Характеристики</h2>
    <div class="space-y-2.5 sm:space-y-3">
        @if($product->sku)
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-0.5 sm:gap-2">
                <span class="font-medium text-slate-700 min-w-fit text-sm">Артикул:</span>
                <span class="text-slate-600 text-sm text-right sm:text-left flex-1">{{ $product->sku }}</span>
            </div>
        @endif
        @if($product->proizvoditel)
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-0.5 sm:gap-2">
                <span class="font-medium text-slate-700 min-w-fit text-sm">Производитель:</span>
                <span class="text-slate-600 text-sm text-right sm:text-left flex-1">{{ $product->proizvoditel }}</span>
            </div>
        @endif
        @if($product->kategoriya)
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-0.5 sm:gap-2">
                <span class="font-medium text-slate-700 min-w-fit text-sm">Категория:</span>
                <span class="text-slate-600 text-sm text-right sm:text-left flex-1">{{ $product->kategoriya }}</span>
            </div>
        @endif
        @if($product->material)
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-0.5 sm:gap-2">
                <span class="font-medium text-slate-700 min-w-fit text-sm">Материал:</span>
                <span class="text-slate-600 text-sm text-right sm:text-left flex-1">{{ $product->material }}</span>
            </div>
        @endif
    </div>

    <button
        type="button"
        class="mt-4 w-full btn-ghost justify-center"
        @click="window.productTabs?.openCharacteristics?.()">
        Смотреть все характеристики
    </button>
</div>
