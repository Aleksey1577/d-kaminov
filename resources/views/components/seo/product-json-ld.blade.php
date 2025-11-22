{{-- resources/views/components/seo/product-json-ld.blade.php --}}

<script type="application/ld+json">
{
  "@context": "https://schema.org ",
  "@type": "Product",
  "name": "{{ $product->naimenovanie }}",
  "image": "{{ asset($product->image_url) }}",
  "description": "{{ strip_tags($product->opisanie) }}",
  "sku": "{{ $product->article }}",
  "brand": {
    "@type": "Brand",
    "name": "{{ $product->proizvoditel }}"
  },
  "offers": {
    "@type": "Offer",
    "price": "{{ $product->display_price }}",
    "priceCurrency": "RUB",
    "availability": "{{ $product->v_nalichii ? 'https://schema.org/InStock ' : 'https://schema.org/OutOfStock ' }}"
  }
}
</script>