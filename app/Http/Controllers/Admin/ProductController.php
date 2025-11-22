<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Список товаров
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('search')) {
            $query->where('naimenovanie', 'like', "%{$request->search}%")
                  ->orWhere('sku', 'like', "%{$request->search}%");
        }

        $products = $query->paginate(10)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    // Форма создания
    public function create()
    {
        return view('admin.products.create');
    }

    // Сохранение товара
    public function store(Request $request)
    {
        $data = $request->validate([
            'naimenovanie' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'kategoriya' => 'required|string|max:255',
            'v_nalichii_na_sklade' => 'required|in:Да,Нет',
            'opisanije' => 'nullable|string',
            'sku' => 'nullable|string|max:50',
            'proizvoditel' => 'nullable|string|max:255',
            'price2' => 'nullable|numeric|min:0',
            'material' => 'nullable|string|max:255',
            'vysota' => 'nullable|string|max:50',
            'shirina' => 'nullable|string|max:50',
            'glubina' => 'nullable|string|max:50',
            'ves' => 'nullable|string|max:50',
            'tsvet' => 'nullable|string|max:255',
            'garantiya' => 'nullable|string|max:255',
            'image_url_1' => 'nullable|url',
            'image_url_2' => 'nullable|url',
            'image_url_3' => 'nullable|url',
        ]);

        Product::create($data);

        return redirect()->route('admin.products')->with('success', 'Товар создан');
    }

    // Форма редактирования
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    // Обновление товара
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'naimenovanie' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'kategoriya' => 'required|string|max:255',
            'v_nalichii_na_sklade' => 'required|in:Да,Нет',
            'opisanije' => 'nullable|string',
            'sku' => 'nullable|string|max:50',
            'proizvoditel' => 'nullable|string|max:255',
            'price2' => 'nullable|numeric|min:0',
            'material' => 'nullable|string|max:255',
            'vysota' => 'nullable|string|max:50',
            'shirina' => 'nullable|string|max:50',
            'glubina' => 'nullable|string|max:50',
            'ves' => 'nullable|string|max:50',
            'tsvet' => 'nullable|string|max:255',
            'garantiya' => 'nullable|string|max:255',
            'image_url_1' => 'nullable|url',
            'image_url_2' => 'nullable|url',
            'image_url_3' => 'nullable|url',
        ]);

        $product->update($data);

        return redirect()->route('admin.products')->with('success', 'Товар обновлён');
    }

    // Удаление товара
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products')->with('success', 'Товар удалён');
    }
}