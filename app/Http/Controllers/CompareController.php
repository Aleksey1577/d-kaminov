<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CompareController extends Controller
{
    public function index()
    {
        $compareIds = session('compare', []);
        if (empty($compareIds) && request()->hasCookie('compare')) {
            $compareIds = json_decode(request()->cookie('compare'), true) ?? [];
            session(['compare' => $compareIds]);
        }
        $products = Product::whereIn('product_id', $compareIds)->get();
        return view('compare', compact('products', 'compareIds'));
    }

    public function add(Request $request, $productId)
    {
        $product = Product::where('product_id', $productId)->firstOrFail();
        $compare = session('compare', []);
        if (count($compare) >= 4) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Максимум 4 товара в сравнении'], 422);
            }
            return redirect()->back()->with('error', 'Максимум 4 товара в сравнении');
        }
        if (!in_array($product->product_id, $compare)) {
            $compare[] = $product->product_id;
            session(['compare' => $compare]);
        }
        $cookie = Cookie::make('compare', json_encode($compare), 60 * 24 * 30);
        if (request()->ajax()) {
            return response()->json([
                'status' => 'added',
                'count' => count($compare)
            ])->withCookie($cookie);
        }
        return redirect()->back()->with('success', 'Товар добавлен в сравнение')->withCookie($cookie);
    }

    public function remove(Request $request, $productId)
    {
        $product = Product::where('product_id', $productId)->firstOrFail();

        // Улучшенная проверка: пропускаем если _method = DELETE или AJAX POST
        if ($request->method() !== 'DELETE' && $request->input('_method') !== 'DELETE') {
            if ($request->ajax()) {
                return response()->json(['error' => 'Неверный метод запроса'], 400); // 400 вместо 405 для AJAX
            }
            return redirect()->back()->with('error', 'Неверный метод запроса');
        }

        $compare = session('compare', []);
        if (($key = array_search($product->product_id, $compare)) !== false) {
            unset($compare[$key]);
            $compare = array_values($compare);
            session(['compare' => $compare]);
        }
        $cookie = Cookie::make('compare', json_encode($compare), 60 * 24 * 30);
        if ($request->ajax()) {
            return response()->json([
                'status' => 'removed',
                'count' => count($compare)
            ])->withCookie($cookie);
        }
        return redirect()->back()->with('success', 'Товар удалён из сравнения')->withCookie($cookie);
    }
}