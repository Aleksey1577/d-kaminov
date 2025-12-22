<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        $idsToResolve = [];
        foreach ($cart as $line) {
            if (!empty($line['slug'])) {
                continue;
            }
            $id = $line['parent_id'] ?? $line['product_id'] ?? null;
            if ($id !== null) {
                $idsToResolve[] = (int) $id;
            }
        }

        if (!empty($idsToResolve)) {
            $slugsById = Product::query()
                ->whereIn('product_id', array_values(array_unique($idsToResolve)))
                ->pluck('slug', 'product_id');

            foreach ($cart as $k => $line) {
                if (!empty($line['slug'])) {
                    continue;
                }
                $id = (int) ($line['parent_id'] ?? $line['product_id'] ?? 0);
                if ($id > 0 && isset($slugsById[$id])) {
                    $cart[$k]['slug'] = $slugsById[$id];
                }
            }
        }

        return view('cart', compact('cart'))
            ->with('breadcrumbs', [
                ['name' => 'Главная', 'url' => route('home')],
                ['name' => 'Корзина', 'url' => null],
            ]);
    }

    public function add(Request $request, $productId)
    {

        $parent = Product::where('product_id', $productId)->firstOrFail();

        $variantId = $request->input('variant_id');
        $qty       = max(1, (int) $request->input('quantity', 1));

        $item = $variantId
            ? Product::where('product_id', $variantId)->where('tip_stroki', 'variant')->first()
            : null;

        if (!$item) {
            $item = $parent;
        }

        $price = (float) ($item->display_price ?? $item->price ?? 0);

        if ($price <= 0) {
            $fallback = Product::where('tip_stroki', 'product_variant')
                ->where('naimenovanie', $parent->naimenovanie)
                ->orderBy('price')
                ->first();

            if ($fallback) {
                $price = (float) ($fallback->display_price ?? $fallback->price ?? 0);
            }
        }

        if ($price <= 0) {
            return back()->with('error', 'Не удалось определить цену выбранного варианта.');
        }

        $displayName = $parent->naimenovanie;
        if ($item->tip_stroki === 'variant') {
            if (!empty($item->naimenovanie_artikula)) {
                $displayName .= ' (' . $item->naimenovanie_artikula . ')';
            } elseif (!empty($item->sku)) {
                $displayName .= ' (' . $item->sku . ')';
            }
        }

        $lineKey = $variantId ?: $parent->product_id;

        $cart = session()->get('cart', []);

        if (isset($cart[$lineKey])) {
            $cart[$lineKey]['quantity'] += $qty;

            $cart[$lineKey]['price'] = $price;
        } else {
            $cart[$lineKey] = [
                'product_id'   => $item->product_id,
                'parent_id'    => $parent->product_id,
                'slug'         => $parent->slug,
                'naimenovanie' => $displayName,
                'price'        => $price,
                'image_url'    => $item->image_url ?: $parent->image_url,
                'quantity'     => $qty,
                'sku'          => $item->sku ?: $parent->sku,
                'valyuta'      => $item->valyuta,
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax()) {
            $count = collect($cart)->sum('quantity');
            return response()->json(['status' => 'ok', 'count' => $count]);
        }

        return redirect()->back()->with('success', 'Товар добавлен в корзину!');
    }

    public function remove(Request $request, $productId)
    {
        if ($request->method() !== 'DELETE') {
            return redirect()->route('cart')->with('error', 'Неверный метод запроса');
        }

        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart')->with('success', 'Товар удалён из корзины');
    }
}
