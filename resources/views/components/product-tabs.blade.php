{{-- resources/views/components/product-tabs.blade.php --}}

<div
    x-data="{
        tab: 'description',
        activateCharacteristics() {
            this.tab = 'characteristics';
            this.$nextTick(() => {
                const anchor = document.getElementById('characteristics-anchor') || this.$el;
                anchor.scrollIntoView({ behavior: 'smooth', block: 'start' });
                window.scrollBy({ top: -120, behavior: 'auto' });
            });
        }
    }"
    x-init="window.productTabs = { openCharacteristics: () => activateCharacteristics() }"
    id="product-tabs"
    class="mt-6 sm:mt-8">
    <span id="characteristics-anchor" class="block -mt-24 pt-24" aria-hidden="true"></span>
    {{-- Табы --}}
    <div class="-mx-4 px-4 sm:mx-0 sm:px-0 flex mb-4 sm:mb-6 border-b border-amber-100 overflow-x-auto">
        <button
            @click="tab = 'description'"
            :class="tab === 'description'
                ? 'text-orange border-orange'
                : 'text-slate-600 border-transparent hover:text-slate-900'"
            class="px-3 sm:px-4 py-2 text-sm sm:text-base font-semibold border-b-2 whitespace-nowrap transition-colors"
        >
            Описание
        </button>

        <button
            @click="tab = 'characteristics'"
            :class="tab === 'characteristics'
                ? 'text-orange border-orange'
                : 'text-slate-600 border-transparent hover:text-slate-900'"
            class="px-3 sm:px-4 py-2 text-sm sm:text-base font-semibold border-b-2 whitespace-nowrap transition-colors"
        >
            Характеристики
        </button>

        <button
            @click="tab = 'delivery'"
            :class="tab === 'delivery'
                ? 'text-orange border-orange'
                : 'text-slate-600 border-transparent hover:text-slate-900'"
            class="px-3 sm:px-4 py-2 text-sm sm:text-base font-semibold border-b-2 whitespace-nowrap transition-colors"
        >
            Доставка
        </button>

        <button
            @click="tab = 'payment'"
            :class="tab === 'payment'
                ? 'text-orange border-orange'
                : 'text-slate-600 border-transparent hover:text-slate-900'"
            class="px-3 sm:px-4 py-2 text-sm sm:text-base font-semibold border-b-2 whitespace-nowrap transition-colors"
        >
            Оплата
        </button>
    </div>

    {{-- Описание --}}
    <div x-show="tab === 'description'" x-transition
         class="text-gray-700 prose prose-sm max-w-none">
        {!! $product->opisanije !!}
    </div>

    {{-- Характеристики --}}
    <div x-show="tab === 'characteristics'" x-transition
         class="mt-4 text-gray-700">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($product->getFullCharacteristics() as $key => $value)
                <div class="flex text-sm bg-amber-50/60 border border-amber-100 rounded-lg px-3 py-2">
                    <span class="w-1/2 pr-2 font-medium text-slate-800">
                        {{ $key }}:
                    </span>
                    <span class="w-1/2 text-slate-600">
                        {{ $value }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Доставка --}}
    <div x-show="tab === 'delivery'" x-transition
         class="mt-4 text-gray-700">
        <h3 class="text-lg font-semibold mb-3">Способы доставки</h3>

        <div class="space-y-4">
            <div class="p-4 rounded-lg border border-amber-100 bg-amber-50/60">
                <h4 class="text-sm font-semibold text-slate-900">Доставка по Самаре</h4>
                <p class="mt-1 text-sm text-slate-700">
                    <span class="font-medium">Стоимость:</span> 1000 ₽
                </p>
                <p class="text-sm text-slate-700">
                    <span class="font-medium">Срок доставки:</span> от 1 дня (в зависимости от наличия товара).
                </p>
            </div>

            <div class="p-4 rounded-lg border border-amber-100 bg-amber-50/60">
                <h4 class="text-sm font-semibold text-slate-900">Доставка по области (до 50 км от Самары)</h4>
                <p class="mt-1 text-sm text-slate-700">
                    <span class="font-medium">Стоимость:</span> 1500 ₽
                </p>
                <p class="text-sm text-slate-700">
                    <span class="font-medium">Срок доставки:</span> от 1 дня (в зависимости от наличия товара).
                </p>
            </div>

            <div class="p-4 rounded-lg border border-amber-100 bg-amber-50/60">
                <h4 class="text-sm font-semibold text-slate-900">Доставка по области (свыше 50 км от Самары)</h4>
                <p class="mt-1 text-sm text-slate-700">
                    <span class="font-medium">Стоимость:</span> по согласованию с менеджером.
                </p>
                <p class="text-sm text-slate-700">
                    <span class="font-medium">Срок доставки:</span> от 1 дня (в зависимости от наличия товара).
                </p>
            </div>

            <div class="p-4 rounded-lg border border-amber-100 bg-amber-50/60">
                <h4 class="text-sm font-semibold text-slate-900">Самовывоз</h4>
                <p class="mt-1 text-sm text-slate-700">
                    <span class="font-medium">Стоимость:</span> бесплатно.
                </p>
                <p class="text-sm text-slate-700">
                    <span class="font-medium">Адрес:</span> г. Самара, ТЦ Интермебель, Московское шоссе 16 км, 1в ст2, 2 этаж.
                </p>
                <p class="text-sm text-slate-700">
                    <span class="font-medium">Режим работы:</span> будни с 10:00 до 19:30.
                </p>
            </div>
        </div>
    </div>

    {{-- Оплата --}}
    <div x-show="tab === 'payment'" x-transition
         class="mt-4 text-gray-700">
        <h3 class="text-lg font-semibold mb-3">Способы оплаты</h3>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="p-4 rounded-lg border border-amber-100 bg-amber-50/60 text-center">
                <p class="text-sm font-semibold text-slate-900">Оплата наличными</p>
                <p class="mt-2 text-sm text-slate-700">При получении товара.</p>
            </div>

            <div class="p-4 rounded-lg border border-amber-100 bg-amber-50/60 text-center">
                <p class="text-sm font-semibold text-slate-900">Картой онлайн</p>
                <p class="mt-2 text-sm text-slate-700">Безопасный платёж через сайт.</p>
            </div>

            <div class="p-4 rounded-lg border border-amber-100 bg-amber-50/60 text-center">
                <p class="text-sm font-semibold text-slate-900">Безналичный расчёт</p>
                <p class="mt-2 text-sm text-slate-700">Оплата по реквизитам компании.</p>
            </div>
        </div>
    </div>
</div>
