<!-- resources/views/components/product-info.blade.php -->

<div>
    <h1 class="text-3xl font-bold mb-4">{{ $product->naimenovanie }}</h1>

    @php
        // Определяем стартовую цену
        if (!empty($variants) && $variants->isNotEmpty()) {
            if ($product->tip_stroki === 'variant') {
                $currentPrice = $product->price;
            } else {
                $currentPrice = $variants->first()->price;
            }
        } else {
            $currentPrice = $product->price;
        }
    @endphp

    <!-- Отображение цены -->
    <p id="price" class="text-gray-700 font-bold text-2xl mb-2">
        {{ number_format($currentPrice, 2) }} ₽
    </p>

    <p class="text-sm text-gray-500 mb-4">
        {{ $product->v_nalichii_na_sklade === 'Да' ? 'В наличии' : 'Нет в наличии' }}
    </p>

    <!-- Выбор варианта товара -->
    @if(!empty($variants) && $variants->isNotEmpty())
        <div class="mb-6">
            <label for="variant" class="block text-gray-700 font-semibold mb-2">Выберите вариант:</label>
            <select id="variant" class="w-full border-gray-300 rounded-lg p-2"
                    onchange="document.getElementById('price').textContent = parseFloat(this.selectedOptions[0].dataset.price).toFixed(2) + ' ₽'">
                @foreach($variants as $variant)
                    <option value="{{ $variant->product_id }}"
                            data-price="{{ $variant->price }}"
                            {{ ($product->tip_stroki==='variant' && $variant->product_id == $product->product_id) ? 'selected' : '' }}>
                        {{ $variant->naimenovanie_artikula }} — {{ number_format($variant->price, 2) }} ₽
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    <!-- Кнопка В корзину -->
    <form action="{{ route('cart.add', $product->product_id) }}" method="POST" class="mb-6">
        @csrf
        <button type="submit"
                class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
            В корзину
        </button>
    </form>
</div>
