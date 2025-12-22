<?php

namespace App\Http\Controllers;

use App\Services\TelegramNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Ваша корзина пуста');
        }

        return view('checkout', compact('cart'))
            ->with('breadcrumbs', [
                ['name' => 'Главная', 'url' => route('home')],
                ['name' => 'Корзина', 'url' => route('cart')],
                ['name' => 'Оформление заказа', 'url' => null],
            ]);
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

        $total = collect($cart)->sum(function ($item) {
            $price = (float) ($item['price'] ?? 0);
            $qty   = (int)   ($item['quantity'] ?? 0);
            return $price * $qty;
        });

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id'        => Auth::id(),
                'name'           => $validated['name'],
                'email'          => $validated['email'],
                'phone'          => $validated['phone'],
                'address'        => $validated['address'] ?? '',
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
            Log::warning('Order create failed', ['exception' => $e]);
            return back()->withInput()->with('error', 'Не удалось оформить заказ. Попробуйте ещё раз.');
        }

        session()->forget('cart');

        try {
            app(TelegramNotifier::class)->sendOrderCreated($order);
        } catch (\Throwable $e) {
            Log::warning('Telegram order notify failed', ['exception' => $e, 'order_id' => $order->id ?? null]);
        }

        return redirect()->route('thank-you')->with('success', 'Заказ успешно оформлен!');
    }

    public function thankYou()
    {
        return view('thank-you')
            ->with('breadcrumbs', [
                ['name' => 'Главная', 'url' => route('home')],
                ['name' => 'Корзина', 'url' => route('cart')],
                ['name' => 'Оформление заказа', 'url' => route('checkout')],
                ['name' => 'Спасибо за заказ', 'url' => null],
            ]);
    }

    public function show(Order $order)
    {

        if (!Auth::check() || $order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items');

        return view('orders.show', compact('order'));
    }
}
