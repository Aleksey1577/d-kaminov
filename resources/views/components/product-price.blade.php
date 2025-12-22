<div class="surface p-5 sm:p-6 md:p-7 mb-6">
    @php
        // Стартовая цена (если есть варианты — берём либо текущий вариант, либо первый)
        if (!empty($variants) && $variants->isNotEmpty()) {
            $current = $product->tip_stroki === 'variant'
                ? $variants->firstWhere('product_id', $product->product_id)
                : $variants->first();

            $currentPrice = (float) ($current->display_price ?? $current->price ?? 0);
        } else {
            $currentPrice = (float) ($product->display_price ?? $product->price ?? 0);
        }
    @endphp

    <div class="text-center mb-3 sm:mb-4">
        <p id="price"
           data-role="product-price"
           class="text-gray-900 font-extrabold text-2xl sm:text-3xl md:text-4xl tracking-tight mb-2">
            {{ number_format(floor($currentPrice), 0, '', ' ') }} ₽
        </p>

        @if ($product->v_nalichii_na_sklade === 'Да')
            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                В наличии
            </span>
        @else
            <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                Под заказ
            </span>
        @endif
    </div>

    <div class="flex items-center justify-center mb-5">
        <div class="flex text-amber-400">
            @for ($i = 1; $i <= 5; $i++)
                <svg class="w-5 h-5 {{ $i <= 4 ? 'text-amber-400' : 'text-gray-300' }} fill-current" viewBox="0 0 20 20">
                    <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
            @endfor
        </div>
        <span class="ml-2 text-sm text-slate-500">4.5 (12 отзывов)</span>
    </div>

    <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-4" id="add-to-cart-form">
        @csrf

        @if (!empty($variants) && $variants->isNotEmpty())
            <div class="mb-4">
                <label for="variant_id" class="block text-slate-800 font-semibold mb-2 text-sm">
                    Выберите вариант
                </label>

                <select
                    id="variant_id"
                    name="variant_id"
                    class="w-full rounded-xl border border-amber-200 bg-white px-3 py-3 text-sm focus:border-orange focus:ring focus:ring-orange/20">
                    @foreach ($variants as $variant)
                        @php
                            $vPrice = (float) ($variant->display_price ?? $variant->price ?? 0);
                        @endphp
                        <option
                            value="{{ $variant->product_id }}"
                            data-price="{{ $vPrice }}"
                            data-image="{{ $variant->image_url ?? '' }}"
                            {{ $product->tip_stroki === 'variant' && $variant->product_id == $product->product_id ? 'selected' : '' }}>
                            {{ $variant->naimenovanie_artikula ?? ('Вариант #' . $variant->product_id) }}
                            — {{ number_format(floor($vPrice), 0, '', ' ') }} ₽
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="space-y-3">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="flex flex-col">
                    <span class="text-xs text-slate-500 mb-1">Количество</span>
                    <input
                        type="number"
                        name="quantity"
                        min="1"
                        value="1"
                        class="w-24 rounded-xl border border-amber-200 px-3 py-2 text-sm text-center focus:border-orange focus:ring focus:ring-orange/20">
                </div>

                <button
                    type="submit"
                    class="w-full sm:flex-1 btn-primary justify-center">
                    В корзину
                </button>
            </div>
        </div>
    </form>

    @php
        $inFavorites = in_array($product->product_id, session('favorites', []));
        $inCompare = in_array($product->product_id, session('compare', []));
    @endphp

    <div class="mt-4 flex flex-wrap gap-2">
        <form
            action="{{ $inCompare ? route('compare.remove', $product->product_id) : route('compare.add', $product->product_id) }}"
            method="POST"
            class="inline-flex">
            @csrf
            @if($inCompare)
                @method('DELETE')
            @endif
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl border px-3 py-2 text-sm font-semibold transition {{ $inCompare ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-amber-200 text-slate-800 hover:border-orange hover:text-orange' }}">
                <img src="{{ asset('assets/header/sravnenie.svg') }}" alt="" class="w-4 h-4">
                {{ $inCompare ? 'В сравнении' : 'Сравнить' }}
            </button>
        </form>

        <form
            action="{{ $inFavorites ? route('favorites.remove', $product->product_id) : route('favorites.add', $product) }}"
            method="POST"
            class="inline-flex">
            @csrf
            @if($inFavorites)
                @method('DELETE')
            @endif
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl border px-3 py-2 text-sm font-semibold transition {{ $inFavorites ? 'border-red-200 bg-red-50 text-red-600' : 'border-amber-200 text-slate-800 hover:border-orange hover:text-orange' }}">
                <img src="{{ asset('assets/header/favourite-header.svg') }}" alt="" class="w-4 h-4">
                {{ $inFavorites ? 'В избранном' : 'В избранное' }}
            </button>
        </form>

    </div>

    <div class="text-center pt-4 mb-4 text-xs text-slate-500">
        Бесплатная доставка по Самаре от 5000 ₽
    </div>

    <div class="mt-4 pt-4 border-t border-amber-100">
        <p class="text-xs text-slate-500 mb-3">
            Нужна помощь с выбором или есть вопросы по товару?
        </p>

        <div class="flex flex-col sm:flex-row gap-2">

            <a href="tel:+79198055747"
               class="w-full sm:flex-1 inline-flex items-center justify-center gap-2 rounded-full border border-emerald-300 text-emerald-700 bg-white px-3 py-2.5 text-xs sm:text-sm font-semibold hover:bg-emerald-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                <span class="hidden sm:inline">Позвонить</span>
                <span class="sm:hidden">Звонок</span>
            </a>

            <a href="https://t.me/Dom_Kaminov63"
               target="_blank"
               rel="noopener noreferrer"
               class="w-full sm:flex-1 inline-flex items-center justify-center gap-2 rounded-full bg-sky-500 px-3 py-2.5 text-xs sm:text-sm font-semibold text-white hover:bg-sky-600 transition">
                <svg class="w-4 h-4" viewBox="0 0 24 24" aria-hidden="true" fill="currentColor">
                    <path
                        d="M12 0C5.372 0 0 5.373 0 12.001 0 18.628 5.372 24 12 24s12-5.372 12-11.999C24 5.373 18.628 0 12 0zm5.19 7.89l-1.72 8.11c-.13.6-.48.75-.97.47l-2.68-1.98-1.29 1.24c-.14.14-.26.26-.53.26l.19-2.76 5.03-4.54c.22-.19-.05-.3-.34-.11L8.4 12.3 5.7 11.46c-.59-.18-.6-.59.12-.87l11.02-4.25c.5-.18.94.12.82.55z" />
                </svg>
                <span>Написать в Telegram</span>
            </a>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const select = document.getElementById('variant_id');
        const priceEl = document.getElementById('price');

        function formatRUB(n) {
            n = Math.floor(Number(n) || 0);
            return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' ₽';
        }

        function dispatchVariantImage(selectEl) {
            if (!selectEl) return;
            const opt = selectEl.selectedOptions[0];
            const img = opt ? opt.dataset.image : null;

            window.dispatchEvent(new CustomEvent('variant-change', {
                detail: {
                    image: img
                }
            }));
        }

        if (select && priceEl) {
            const apply = () => {
                const opt = select.selectedOptions[0];
                const p = opt ? opt.dataset.price : 0;
                priceEl.textContent = formatRUB(p);

                // обновляем картинку
                dispatchVariantImage(select);
            };

            select.addEventListener('change', apply);
            apply(); // начальное применение (и цена, и картинка)
        }
    });

</script>
