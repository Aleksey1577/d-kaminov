<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart', compact('cart'));
    }

    public function add(Request $request, $productId)
    {
        // Родительский товар (страница которого открыта)
        $parent = Product::where('product_id', $productId)->firstOrFail();

        // Если передали variant_id — используем его как позицию
        $variantId = $request->input('variant_id');
        $qty       = max(1, (int) $request->input('quantity', 1));

        // Товар-источник цены (вариант или сам родитель)
        $item = $variantId
            ? Product::where('product_id', $variantId)->where('tip_stroki', 'variant')->first()
            : null;

        if (!$item) {
            $item = $parent;
        }

        // Цена: display_price (если вы его рассчитываете), иначе price
        $price = (float) ($item->display_price ?? $item->price ?? 0);

        // Фолбэк: если цена 0/NULL, попробуем строку "product_variant" с тем же наименованием
        if ($price <= 0) {
            $fallback = Product::where('tip_stroki', 'product_variant')
                ->where('naimenovanie', $parent->naimenovanie)
                ->orderBy('price') // логика выбора может быть другой
                ->first();

            if ($fallback) {
                $price = (float) ($fallback->display_price ?? $fallback->price ?? 0);
            }
        }

        if ($price <= 0) {
            return back()->with('error', 'Не удалось определить цену выбранного варианта.');
        }

        // Собираем красивое имя: "Наименование товара (Наименование артикула)"
        $displayName = $parent->naimenovanie;
        if ($item->tip_stroki === 'variant') {
            if (!empty($item->naimenovanie_artikula)) {
                $displayName .= ' (' . $item->naimenovanie_artikula . ')';
            } elseif (!empty($item->sku)) {
                $displayName .= ' (' . $item->sku . ')';
            }
        }

        // Ключ позиции: ID варианта, если есть, иначе ID родителя
        $lineKey = $variantId ?: $parent->product_id;

        $cart = session()->get('cart', []);

        if (isset($cart[$lineKey])) {
            $cart[$lineKey]['quantity'] += $qty;
            // Обновим цену на случай, если пересчитали курсы/цены
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

        // Если используете AJAX — можно вернуть JSON; иначе редирект как раньше
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
