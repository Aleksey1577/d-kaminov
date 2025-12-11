<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $static = [
            route('home'),
            route('catalog'),
            route('contacts'),
            route('portfolio'),
            route('montage'),
            route('delivery'),
        ];

        $categories = Category::query()
            ->select(['slug', 'updated_at'])
            ->orderBy('id')
            ->get()
            ->map(fn ($category) => [
                'loc' => route('catalog', ['category' => $category->slug]),
                'lastmod' => optional($category->updated_at)->toAtomString(),
            ]);

        $products = Product::query()
            ->select(['product_id', 'naimenovanie', 'updated_at'])
            ->latest('updated_at')
            ->limit(2000)
            ->get()
            ->map(fn ($product) => [
                'loc' => route('product', ['slug' => $product->slug]),
                'lastmod' => optional($product->updated_at)->toAtomString(),
            ]);

        $urls = collect($static)
            ->map(fn ($url) => ['loc' => $url, 'lastmod' => null])
            ->merge($categories)
            ->merge($products);

        return response()
            ->view('sitemap.xml', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
