<!-- resources/views/components/product-images.blade.php -->

<div x-data="{ activeImage: 0 }" class="relative">
    <div class="w-full h-96 bg-white flex items-center justify-center rounded-lg mb-4 overflow-hidden">
        <img
            :src="[
                @foreach(array_filter([$product->image_url, $product->image_url_1, $product->image_url_2, $product->image_url_3]) as $image)
                    '{{ $image }}'{{ !$loop->last ? ',' : '' }}
                @endforeach
            ][activeImage] || '{{ asset('images/placeholder.png') }}'"
            alt="{{ $product->naimenovanie }}"
            class="max-w-full max-h-full object-contain transition-all duration-300"
        >
    </div>

    @if(array_filter([$product->image_url, $product->image_url_1, $product->image_url_2, $product->image_url_3]))
        <div class="flex space-x-2 overflow-x-auto">
            @foreach(array_filter([$product->image_url, $product->image_url_1, $product->image_url_2, $product->image_url_3]) as $image)
                <img
                    @click="activeImage = {{ $loop->index }}"
                    src="{{ $image }}"
                    alt="{{ $product->naimenovanie }}"
                    class="w-20 h-20 object-cover rounded cursor-pointer"
                    :class="{ 'ring-2 ring-blue-600': activeImage === {{ $loop->index }} }"
                >
            @endforeach
        </div>
    @endif
</div>
