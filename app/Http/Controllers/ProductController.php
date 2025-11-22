<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\SeoService;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        return view('admin.products.index', compact('products'));
    }

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

        $seo = app(SeoService::class)->getProduct([
            'naimenovanie' => $product->naimenovanie,
            'seo_title' => $product->seo_title,
            'seo_description' => $product->seo_description,
            'seo_keywords' => $product->seo_keywords,
        ]);

        return view('product', compact('product', 'variants', 'seo'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'naimenovanie' => 'required|string|max:255',
            'price' => 'required|numeric',
            'kategoriya' => 'required|string',
            'tip_stroki' => 'required|in:product,product_variant,variant',
            'image_url' => 'nullable|string',
            // Добавить другие поля
        ]);

        Product::create($data);
        return redirect()->route('admin.products')->with('success', 'Товар создан.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'naimenovanie' => 'required|string|max:255',
            'price' => 'required|numeric',
            'kategoriya' => 'required|string',
            'tip_stroki' => 'required|in:product,product_variant,variant',
            'image_url' => 'nullable|string',
            // Добавить другие поля
        ]);

        $product->update($data);
        return redirect()->route('admin.products')->with('success', 'Товар обновлен.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products')->with('success', 'Товар удален.');
    }
}
