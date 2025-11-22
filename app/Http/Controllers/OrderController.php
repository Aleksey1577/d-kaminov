<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Ваша корзина пуста');
        }

        return view('checkout', compact('cart'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'pickup_type' => 'required|in:pickup,delivery',
            'payment_method' => 'required|in:cash,card',
        ]);

        if ($request->input('pickup_type') === 'delivery') {
            $request->validate([
                'address' => 'required|string|max:255'
            ]);
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста');
        }

        $order = Order::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address ?? null,
            'pickup_type' => $request->pickup_type,
            'payment_method' => $request->payment_method,
            'total' => collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']),
            'status' => 'pending',
        ]);

        foreach ($cart as $itemId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $itemId,
                'name' => $item['naimenovanie'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'total' => $item['price'] * $item['quantity'],
            ]);
        }

        session()->forget('cart');
        return redirect()->route('thank-you')->with('success', 'Заказ успешно оформлен!');
    }

    public function thankYou()
    {
        return view('thank-you');
    }
}