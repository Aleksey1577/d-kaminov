<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Traits\CommonDataTrait;

class FavoritesController extends Controller
{
    use CommonDataTrait;
    public function index()
    {
        $favoriteIds = session('favorites', []);
        if (empty($favoriteIds) && request()->hasCookie('favorites')) {
            $favoriteIds = json_decode(request()->cookie('favorites'), true) ?? [];
            session(['favorites' => $favoriteIds]);
        }
        $products = Product::whereIn('product_id', $favoriteIds)->get();
        $this->setDisplayPrices($products);
        return view('favorites', compact('products', 'favoriteIds'))
            ->with('breadcrumbs', [
                ['name' => 'Главная', 'url' => route('home')],
                ['name' => 'Избранное', 'url' => null],
            ]);
    }

    public function add(Product $product)
    {
        $favorites = session('favorites', []);
        if (!in_array($product->product_id, $favorites)) {
            $favorites[] = $product->product_id;
            session(['favorites' => $favorites]);
        }
        $cookie = cookie('favorites', json_encode($favorites), 60 * 24 * 30);
        if (request()->ajax()) {
            return response()->json([
                'status' => 'added',
                'count' => count($favorites)  // <-- Добавьте это
            ])->withCookie($cookie);
        }
        return redirect()->back()->with('success', 'Товар добавлен в избранное')->withCookie($cookie);
    }

    public function remove(Request $request, $productId)
    {
        if ($request->input('_method') !== 'DELETE' && $request->method() !== 'DELETE') {
            if ($request->ajax()) {
                return response()->json(['error' => 'Неверный метод запроса'], 405);
            }
            return redirect()->route('favorites')->with('error', 'Неверный метод запроса');
        }
        $favorites = session('favorites', []);
        if (($key = array_search($productId, $favorites)) !== false) {
            unset($favorites[$key]);
            $favorites = array_values($favorites);
            session(['favorites' => $favorites]);
        }
        $cookie = cookie('favorites', json_encode($favorites), 60 * 24 * 30);
        if ($request->ajax()) {
            return response()->json([
                'status' => 'removed',
                'count' => count($favorites)  // <-- Добавьте это
            ])->withCookie($cookie);
        }
        return redirect()->back()->with('success', 'Товар удалён из избранного')->withCookie($cookie);
    }
}
