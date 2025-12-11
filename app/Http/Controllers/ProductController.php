<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
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

        $seo->fill([
            'title'       => $meta['seo_title'] ?? null,
            'description' => $meta['seo_description'] ?? null,
            'keywords'    => $meta['seo_keywords'] ?? null,
            'image'       => $product->thumb_url,
        ])->canonical()->breadcrumb('Главная', route('home'))
          ->breadcrumb('Каталог', route('catalog'))
          ->breadcrumb($product->kategoriya ?? 'Товар', route('catalog', ['category' => $product->kategoriya]))
          ->breadcrumb($product->naimenovanie);

        return view('product', compact('product', 'variants', 'seo', 'meta'));
    }
}
