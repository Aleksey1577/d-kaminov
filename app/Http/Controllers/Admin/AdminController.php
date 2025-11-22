<?php

// app/Http/Controllers/Admin/AdminController.php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // Импортируем базовый контроллер Laravel
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class AdminController extends Controller // Теперь это App\Http\Controllers\Admin\Controller
{
    public function index()
    {
        // Получаем статистику
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $activeUsers = User::where('last_seen', '>', Carbon::now()->subDay())->count();
        $newUsers = User::where('created_at', '>', Carbon::now()->subWeek())->count();

        $orders = Order::latest()->take(5)->get(); // последние 5 заказов

        return view('admin.index', compact(
            'totalProducts', 
            'totalOrders', 
            'activeUsers', 
            'newUsers',
            'orders'
        ));
    }
}