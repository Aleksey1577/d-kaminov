<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProductController; // фронтовый контроллер товара
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\CategoryController;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\ProductController as AdminProductController; // АДМИНСКИЙ контроллер товара
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\Admin\PortfolioController as AdminPortfolioController;
use App\Http\Controllers\SitemapController;

use App\Http\Middleware\LogVisit;

// Карта сайта (не логируем визиты)
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

// Публичная часть (с логированием визитов)
Route::middleware(LogVisit::class)->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
    Route::get('/search', [CatalogController::class, 'search'])->name('search');

    // Страница товара (фронт)
    Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product');

    // Статические страницы
    Route::get('/delivery', [StaticPageController::class, 'delivery'])->name('delivery');
    Route::get('/montage', [StaticPageController::class, 'montage'])->name('montage');
    Route::get('/portfolio', [StaticPageController::class, 'portfolio'])->name('portfolio');
    Route::get('/contacts', [StaticPageController::class, 'contacts'])->name('contacts');
    Route::get('/privacy-policy', [StaticPageController::class, 'privacyPolicy'])->name('privacy.policy');
    Route::post('/callback', [StaticPageController::class, 'callback'])->middleware('throttle:callback')->name('callback');

    // Оформление заказа (публичная часть)
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'store'])->middleware('throttle:checkout')->name('order.store');
    Route::get('/thank-you', [OrderController::class, 'thankYou'])->name('thank-you');

    // Корзина
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::match(['POST', 'DELETE'], '/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');

    // Избранное
    Route::get('/favorites', [FavoritesController::class, 'index'])->name('favorites');
    Route::post('/favorites/add/{product}', [FavoritesController::class, 'add'])->name('favorites.add');
    Route::match(['POST', 'DELETE'], '/favorites/remove/{productId}', [FavoritesController::class, 'remove'])->name('favorites.remove');

    // Сравнение
    Route::get('/compare', [CompareController::class, 'index'])->name('compare');
    Route::post('/compare/add/{productId}', [CompareController::class, 'add'])->name('compare.add');
    Route::match(['POST', 'DELETE'], '/compare/remove/{productId}', [CompareController::class, 'remove'])->name('compare.remove');
});

// Личный кабинет (только авторизованные)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
});

// Аутентификация
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Админка
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');

    // ТОВАРЫ — используем АДМИНСКИЙ контроллер
    Route::get('/products', [AdminProductController::class, 'index'])->name('products');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    // Категории
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Аналитика
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');

    // Заказы в админке
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update.status');

    // Пользователи
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Слайды
    Route::resource('slides', SlideController::class)->except(['show']);

    // Портфолио
    Route::get('/portfolio', [AdminPortfolioController::class, 'index'])->name('portfolio.index');
    Route::get('/portfolio/create', [AdminPortfolioController::class, 'create'])->name('portfolio.create');
    Route::post('/portfolio', [AdminPortfolioController::class, 'store'])->name('portfolio.store');
    Route::get('/portfolio/{portfolioItem}/edit', [AdminPortfolioController::class, 'edit'])->name('portfolio.edit');
    Route::put('/portfolio/{portfolioItem}', [AdminPortfolioController::class, 'update'])->name('portfolio.update');
    Route::delete('/portfolio/{portfolioItem}', [AdminPortfolioController::class, 'destroy'])->name('portfolio.destroy');
});
