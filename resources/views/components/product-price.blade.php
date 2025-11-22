{{-- resources/views/components/product-price.blade.php --}}

<div class="mb-6 bg-white rounded-lg shadow-md p-6 mx-4 md:mx-0">
    @php
        // Стартовая цена (если есть варианты — берём либо текущий вариант, либо первый)
        $currentPrice = null;
        if (!empty($variants) && $variants->isNotEmpty()) {
            $current = $product->tip_stroki === 'variant'
                ? $variants->firstWhere('product_id', $product->product_id)
                : $variants->first();
            $currentPrice = (float)($current->display_price ?? $current->price ?? 0);
        } else {
            $currentPrice = (float)($product->display_price ?? $product->price ?? 0);
        }
    @endphp

    <!-- Заголовок с ценой -->
    <div class="text-center mb-4">
        <p id="price" class="text-gray-700 font-bold text-3xl mb-1" data-role="product-price">
            {{ number_format(floor($currentPrice), 0, '', ' ') }} ₽
        </p>

        @if($product->v_nalichii_na_sklade === 'Да')
            <div class="flex items-center justify-center text-green-600 text-sm font-medium">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                В наличии
            </div>
        @else
            <div class="flex items-center justify-center text-orange-600 text-sm font-medium">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                </svg>
                Под заказ
            </div>
        @endif
    </div>

    <!-- Рейтинг (демо) -->
    <div class="flex items-center justify-center mb-4">
        <div class="flex text-yellow-400">
            @for($i = 1; $i <= 5; $i++)
                <svg class="w-5 h-5 fill-current {{ $i <= 4 ? '' : 'text-gray-300' }}" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
            @endfor
        </div>
        <span class="ml-2 text-sm text-gray-500">4.5 (12 отзывов)</span>
    </div>

    <!-- Форма добавления в корзину (select ВНУТРИ формы и имеет name="variant_id") -->
    <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-4" id="add-to-cart-form">
        @csrf

        @if(!empty($variants) && $variants->isNotEmpty())
            <div class="mb-4">
                <label for="variant_id" class="block text-gray-700 font-semibold mb-2">Выберите вариант:</label>
                <select
                    id="variant_id"
                    name="variant_id"
                    class="w-full border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    @foreach($variants as $variant)
                        @php
                            $vPrice = (float)($variant->display_price ?? $variant->price ?? 0);
                        @endphp
                        <option
                            value="{{ $variant->product_id }}"
                            data-price="{{ $vPrice }}"
                            {{ ($product->tip_stroki === 'variant' && $variant->product_id == $product->product_id) ? 'selected' : '' }}
                        >
                            {{ $variant->naimenovanie_artikula ?? ('Вариант #' . $variant->product_id) }}
                            — {{ number_format(floor($vPrice), 0, '', ' ') }} ₽
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="flex items-center gap-3">
            <input type="number" name="quantity" min="1" value="1" class="border rounded-lg p-2 w-24">
            <button type="submit"
                class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 font-semibold shadow-sm">
                В корзину
            </button>
        </div>
    </form>

    <!-- Доп. инфо -->
    <div class="text-center mb-4 text-sm text-gray-500 space-y-1">
        <p>Бесплатная доставка по Самаре от 5000 ₽</p>
        <p>Гарантия возврата 14 дней</p>
    </div>

    <!-- Кнопки связи -->
    <div class="flex space-x-3">
        <a href="tel:+79198055747"
           class="flex-1 flex items-center justify-center bg-gradient-to-r from-green-600 to-green-700 text-white py-3 rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-200 shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
            Позвонить
        </a>
        <a href="https://t.me/+79198055747" target="_blank"
           class="flex-1 flex items-center justify-center bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path fill-rule="evenodd" d="M12.002 0C5.373 0 0 5.373 0 12.002c0 6.628 5.373 12.002 12.002 12.002 6.628 0 12.002-5.374 12.002-12.002C24.004 5.373 18.63 0 12.002 0zm5.258 17.996c-.283.14-.64.04-1.06-.26l-2.78-1.67c-.27-.16-.43-.3-.43-.47 0-.17.16-.31.43-.47l2.78-1.67c.42-.25.78-.35 1.06-.26.28.09.5.31.5.59v3.88c0 .28-.22.5-.5.59zm-1.52-5.5l-3.5 2.1c-.27.16-.43.3-.43.47 0 .17.16.31.43.47l3.5 2.1c.42.25.78.35 1.06.26.28-.09.5-.31.5-.59v-4.76c0-.28-.22-.5-.5-.59-.28-.09-.64.01-1.06.26z" clip-rule="evenodd"/>
            </svg>
            Написать в Telegram
        </a>
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

    if (select && priceEl) {
        const apply = () => {
            const opt = select.selectedOptions[0];
            const p = opt ? opt.dataset.price : 0;
            priceEl.textContent = formatRUB(p);
        };
        select.addEventListener('change', apply);
        apply();
    }
});
</script>
