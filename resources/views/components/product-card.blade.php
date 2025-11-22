<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition flex flex-col h-full">
    <!-- Изображение товара -->
    <a href="{{ route('product', $product->slug) }}" class="block flex-grow">
        <div class="relative w-full h-64">
            <img
                src="{{ $product->image_url ?: asset('images/placeholder.png') }}"
                alt="{{ $product->naimenovanie }}"
                class="w-full h-full object-contain">
        </div>
    </a>

    <div class="p-4 flex flex-col">
        <!-- Наименование -->
        <a href="{{ route('product', $product->slug) }}" class="block">
            <h3 class="text-lg font-semibold hover:text-orange">{{ $product->naimenovanie }}</h3>
        </a>

        <!-- Чекбокс сравнения -->
        <form
            class="compare-form"
            action="{{ in_array($product->product_id, session('compare', [])) ? route('compare.remove', $product->product_id) : route('compare.add', $product->product_id) }}"
            method="POST"
            data-is-compared="{{ in_array($product->product_id, session('compare', [])) ? 'true' : 'false' }}"
            data-add-url="{{ route('compare.add', $product->product_id) }}" 
            data-remove-url="{{ route('compare.remove', $product->product_id) }}">
            @csrf
            @if (in_array($product->product_id, session('compare', [])))
            @method('DELETE')
            @endif
            <label class="inline-flex items-center mt-2">
                <input type="checkbox" class="form-checkbox h-5 w-5 text-orange-600" name="compare"
                    {{ in_array($product->product_id, session('compare', [])) ? 'checked' : '' }}>
                <span class="ml-2 text-gray-700">Сравнить</span>
            </label>
        </form>

        <!-- Блок с ценой и кнопками -->
        <div class="flex items-center justify-between mt-4">
            <!-- Цена -->
            <span class="text-xl font-bold text-gray-800">
                {{ number_format(floor($product->display_price ?? 0), 0, '', '') }} ₽
            </span>

            <!-- Кнопки действий -->
            <div class="flex space-x-2">
                <!-- Кнопка избранного -->
                <form
                    class="favorites-form"
                    action="{{ in_array($product->product_id, session('favorites', [])) ? route('favorites.remove', $product->product_id) : route('favorites.add', $product) }}"
                    method="POST"
                    data-is-favorite="{{ in_array($product->product_id, session('favorites', [])) ? 'true' : 'false' }}"
                    data-add-url="{{ route('favorites.add', $product) }}"
                    data-remove-url="{{ route('favorites.remove', $product->product_id) }}">
                    @csrf
                    @if (in_array($product->product_id, session('favorites', [])))
                    @method('DELETE')
                    @endif
                    <button type="submit" class="favorites-button p-2 rounded-full transition {{ in_array($product->product_id, session('favorites', [])) ? 'bg-red-100 text-red-500' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}" title="{{ in_array($product->product_id, session('favorites', [])) ? 'Удалить из избранного' : 'Добавить в избранное' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="favorites-icon h-5 w-5" fill="{{ in_array($product->product_id, session('favorites', [])) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                </form>

                <!-- Кнопка корзины -->
                <form action="{{ route('cart.add', $product) }}"
                    method="POST"
                    onsubmit="sessionStorage.setItem('scrollPos', window.pageYOffset);">
                    @csrf
                    <button
                        type="submit"
                        class="p-2 bg-orange hover:bg-orange-700 text-white rounded-full transition"
                        title="Добавить в корзину">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>