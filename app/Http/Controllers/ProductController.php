<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\SeoService;

class ProductController extends Controller
{
    // Страница товара (фронт)
    public function show($slug)
    {
        $parts = explode('-', $slug);
        $productId = array_pop($parts);

        $product = Product::where('product_id', $productId)->firstOrFail();

        if ($slug !== $product->slug) {
            return redirect()->route('product', $product->slug, 301);
        }

        $variants = Product::where('tip_stroki', 'variant')
            ->where('naimenovanie', $product->naimenovanie)
            ->get();

        $seo = app(SeoService::class);
        $meta = $seo->getProduct([
            'naimenovanie'    => $product->naimenovanie,
            'seo_title'       => $product->seo_title,
            'seo_description' => $product->seo_description,
            'seo_keywords'    => $product->seo_keywords,
        ]);

        $productUrl = route('product', ['slug' => $product->slug]);

        $variantPrices = $variants
            ->pluck('price')
            ->filter(fn($v) => is_numeric($v))
            ->map(fn($v) => (float) $v)
            ->values();
        $lowPrice = $variantPrices->isNotEmpty()
            ? (float) $variantPrices->min()
            : (float) ($product->price ?? 0);
        $highPrice = $variantPrices->isNotEmpty()
            ? (float) $variantPrices->max()
            : $lowPrice;

        $offers = null;
        if ($variantPrices->count() > 1 && $lowPrice !== $highPrice) {
            $offers = [
                '@type' => 'AggregateOffer',
                'url' => $productUrl,
                'priceCurrency' => 'RUB',
                'lowPrice' => number_format($lowPrice, 0, '.', ''),
                'highPrice' => number_format($highPrice, 0, '.', ''),
                'offerCount' => $variantPrices->count(),
            ];
        }

        $seo->forProduct([
            'naimenovanie' => $product->naimenovanie,
            'seo_title' => $meta['seo_title'] ?? null,
            'seo_description' => $meta['seo_description'] ?? null,
            'seo_keywords' => $meta['seo_keywords'] ?? null,
            'opisanije' => $product->opisanije,
            'sku' => $product->sku,
            'brand' => $product->proizvoditel,
            'image_abs' => $product->thumb_url,
            'price' => $lowPrice,
            'v_nalichii_na_sklade' => $product->v_nalichii_na_sklade,
            'url' => $productUrl,
            'offers' => $offers,
        ])
            ->canonical($productUrl)
            ->breadcrumb('Главная', route('home'))
            ->breadcrumb('Каталог', route('catalog'))
            ->breadcrumb($product->kategoriya ?? 'Товар', route('catalog', ['category' => $product->kategoriya]))
            ->breadcrumb($product->naimenovanie);

        return view('product', compact('product', 'variants', 'seo', 'meta'));
    }
}
