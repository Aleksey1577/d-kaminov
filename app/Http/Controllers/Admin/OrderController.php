<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{

public function index(Request $request)
{
    $query = Order::with('items')->latest();

    if ($request->has('status')) {
        $query->where('status', $request->status);
    }

    $orders = $query->paginate(10)->withQueryString();

    return view('admin.orders.index', compact('orders', 'request'));
}

    public function show(Order $order)
    {
        $order->load('items');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Order $order, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled'
        ]);

        $order->update(['status' => $validated['status']]);

        return back()->with('success', 'Статус обновлён');
    }
}
