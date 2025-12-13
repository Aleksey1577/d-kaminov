<div class="surface overflow-hidden flex flex-col h-full transition-transform duration-200 hover:-translate-y-0.5">
    <!-- Изображение товара -->
    <a href="{{ route('product', $product->slug) }}" class="block flex-grow">
        <div class="relative w-full h-64 bg-white flex items-center justify-center">
            <img
                src="{{ $product->image_url ?: asset('assets/placeholder.png') }}"
                alt="{{ $product->naimenovanie }}"
                class="w-full h-full object-contain mix-blend-multiply"
                loading="lazy"
                decoding="async">
        </div>
    </a>

    <div class="p-5 flex flex-col gap-3">
        <!-- Наименование -->
        <a href="{{ route('product', $product->slug) }}" class="block">
            <h3 class="text-lg font-semibold leading-tight text-slate-900 hover:text-orange line-clamp-2">
                {{ $product->naimenovanie }}
            </h3>
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
            <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox"
                    class="h-4 w-4 rounded border-amber-200 text-orange focus:ring-orange"
                    name="compare"
                    {{ in_array($product->product_id, session('compare', [])) ? 'checked' : '' }}>
                <span>Сравнить</span>
            </label>
        </form>

        <!-- Блок с ценой и кнопками -->
        <div class="flex items-center justify-between pt-3 border-t border-amber-100">
            <!-- Цена -->
            <span class="text-2xl font-bold text-slate-900">
                {{ number_format(floor($product->display_price ?? 0), 0, '', '') }} ₽
            </span>

            <!-- Кнопки действий -->
            <div class="flex items-center gap-2">
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
                    <button
                        type="submit"
                        class="favorites-button p-2 rounded-xl border transition {{ in_array($product->product_id, session('favorites', [])) ? 'border-red-200 bg-red-50 text-red-500' : 'border-amber-200 bg-white/80 text-slate-700 hover:border-orange hover:text-orange' }}"
                        title="{{ in_array($product->product_id, session('favorites', [])) ? 'Удалить из избранного' : 'Добавить в избранное' }}">
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
                        class="btn-primary"
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
