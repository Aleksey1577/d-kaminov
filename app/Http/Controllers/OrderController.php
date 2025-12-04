<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Ваша корзина пуста');
        }

        return view('checkout', compact('cart'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email',
            'phone'          => 'required|string|max:20',
            'pickup_type'    => 'required|in:pickup,delivery',
            'payment_method' => 'required|in:cash,card',
            'address'        => 'nullable|required_if:pickup_type,delivery|string|max:255',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Корзина пуста');
        }

        // Подсчёт суммы
        $total = collect($cart)->sum(function ($item) {
            $price = (float) ($item['price'] ?? 0);
            $qty   = (int)   ($item['quantity'] ?? 0);
            return $price * $qty;
        });

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id'        => Auth::id(), // null, если гость
                'name'           => $validated['name'],
                'email'          => $validated['email'],
                'phone'          => $validated['phone'],
                'address'        => $validated['address'] ?? null,
                'pickup_type'    => $validated['pickup_type'],
                'payment_method' => $validated['payment_method'],
                'total'          => $total,
                'status'         => 'pending',
            ]);

            foreach ($cart as $productId => $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => (int) $productId,
                    'name'       => (string) ($item['naimenovanie'] ?? $item['name'] ?? 'Товар'),
                    'price'      => (float)  ($item['price'] ?? 0),
                    'quantity'   => (int)    ($item['quantity'] ?? 0),
                    'total'      => (float)  (($item['price'] ?? 0) * ($item['quantity'] ?? 0)),
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Не удалось оформить заказ. Попробуйте ещё раз.');
        }

        session()->forget('cart');

        return redirect()->route('thank-you')->with('success', 'Заказ успешно оформлен!');
    }

    public function thankYou()
    {
        return view('thank-you');
    }

    // Страница конкретного заказа для пользователя
    public function show(Order $order)
    {
        // Разрешаем смотреть заказ, только если это его владелец
        if (!Auth::check() || $order->user_id !== Auth::id()) {
            abort(403);
        }

        // подтянем позиции
        $order->load('items');

        return view('orders.show', compact('order'));
    }
}
