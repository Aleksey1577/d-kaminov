{{-- resources/views/components/product-images.blade.php --}}

@php
    $images = [];

    $fields = array_merge(
        ['image_url'],
        array_map(fn($i) => "image_url_{$i}", range(1, 20))
    );

    foreach ($fields as $field) {
        $value = $product->$field ?? null;
        if (empty($value)) {
            continue;
        }

        // Абсолютные URL оставляем как есть
        if (\Illuminate\Support\Str::startsWith($value, ['http://', 'https://', '//'])) {
            $images[] = $value;
        } else {
            // Относительные пути типа /assets/... превращаем в полный URL
            $images[] = asset(ltrim($value, '/'));
        }
    }

    $placeholder = asset('images/placeholder.png');
@endphp

<div
    class="relative"
    x-data='{
        images: @json($images),
        activeImage: 0,
        placeholder: @json($placeholder),

        setMainImage(url) {
            if (!url) return;

            const idx = this.images.indexOf(url);
            if (idx !== -1) {
                this.activeImage = idx;
                return;
            }

            this.images.unshift(url);
            this.activeImage = 0;
        }
    }'
    @variant-change.window="setMainImage($event.detail.image)"
>
    {{-- Основное изображение --}}
    <div class="w-full h-72 sm:h-96 surface-quiet flex items-center justify-center mb-3 sm:mb-4 overflow-hidden">
        <img
            src="{{ $images[0] ?? $placeholder }}"
            :src="images.length ? images[activeImage] : placeholder"
            alt="{{ $product->naimenovanie }}"
            class="max-w-full max-h-full object-contain transition-all duration-300"
            loading="eager"
            fetchpriority="high"
            decoding="async"
        >
    </div>

    {{-- Галерея превью --}}
    @if(count($images))
        <div class="flex space-x-3 overflow-x-auto px-1 py-3 sm:py-4 -mx-1 sm:mx-0">
            <template x-for="(image, index) in images" :key="index">
                <img
                    @click="activeImage = index"
                    :src="image"
                    alt="{{ $product->naimenovanie }}"
                    class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-lg cursor-pointer flex-shrink-0 transition-all border border-amber-100 bg-white"
                    loading="lazy"
                    decoding="async"
                    :class="{ 'ring-2 ring-orange': activeImage === index }"
                >
            </template>
        </div>
    @endif
</div>
