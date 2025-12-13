@php
    $image = (string) ($product->thumb_url ?? asset('images/no-image.png'));
    $imageAbs = \Illuminate\Support\Str::startsWith($image, ['http://', 'https://', '//']) ? $image : url($image);

    $rawStock = (string) ($product->v_nalichii_na_sklade ?? '');
    $availability = $rawStock === 'Да' ? 'https://schema.org/InStock' : 'https://schema.org/PreOrder';

    $price = (float) ($product->display_price ?? $product->price ?? 0);

    $data = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => (string) ($product->naimenovanie ?? ''),
        'image' => [$imageAbs],
        'description' => \Illuminate\Support\Str::limit(strip_tags((string) ($product->opisanije ?? '')), 500),
        'sku' => $product->sku ?: null,
        'brand' => $product->proizvoditel ? ['@type' => 'Brand', 'name' => (string) $product->proizvoditel] : null,
        'offers' => [
            '@type' => 'Offer',
            'url' => route('product', ['slug' => $product->slug]),
            'priceCurrency' => 'RUB',
            'price' => $price > 0 ? number_format($price, 0, '.', '') : null,
            'availability' => $availability,
        ],
    ];

    $data = array_filter($data, fn($v) => !is_null($v) && $v !== '');
    $data['offers'] = array_filter($data['offers'] ?? [], fn($v) => !is_null($v) && $v !== '');
@endphp

<script type="application/ld+json">@json($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)</script>
