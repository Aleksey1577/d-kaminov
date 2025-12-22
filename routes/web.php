<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProductController;
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
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\Admin\PortfolioController as AdminPortfolioController;
use App\Http\Controllers\SitemapController;

use App\Http\Middleware\LogVisit;

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('/search', function () {
    return redirect()->route('search', request()->query(), 301);
});
Route::get('/product/{slug}', function (string $slug) {
    return redirect()->route('product', ['slug' => $slug] + request()->query(), 301);
});
Route::permanentRedirect('/delivery', '/dostavka');
Route::permanentRedirect('/montage', '/montazh');
Route::permanentRedirect('/portfolio', '/raboty');
Route::permanentRedirect('/contacts', '/kontakty');
Route::permanentRedirect('/privacy-policy', '/politika-konfidencialnosti');
Route::permanentRedirect('/compare', '/sravnenie');
Route::permanentRedirect('/favorites', '/izbrannoe');
Route::permanentRedirect('/cart', '/korzina');
Route::permanentRedirect('/checkout', '/oformlenie-zakaza');
Route::permanentRedirect('/thank-you', '/spasibo');
Route::permanentRedirect('/login', '/vhod');
Route::permanentRedirect('/register', '/registraciya');
Route::permanentRedirect('/profile', '/kabinet');

Route::middleware(LogVisit::class)->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/catalog/{category?}', [CatalogController::class, 'index'])->name('catalog');
    Route::get('/poisk', [CatalogController::class, 'search'])->name('search');

    Route::get('/tovar/{slug}', [ProductController::class, 'show'])->name('product');

    Route::get('/dostavka', [StaticPageController::class, 'delivery'])->name('delivery');
    Route::get('/montazh', [StaticPageController::class, 'montage'])->name('montage');
    Route::get('/raboty', [StaticPageController::class, 'portfolio'])->name('portfolio');
    Route::get('/kontakty', [StaticPageController::class, 'contacts'])->name('contacts');
    Route::get('/politika-konfidencialnosti', [StaticPageController::class, 'privacyPolicy'])->name('privacy.policy');
    Route::post('/obratnyj-zvonok', [StaticPageController::class, 'callback'])->middleware('throttle:callback')->name('callback');

    Route::get('/oformlenie-zakaza', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/oformlenie-zakaza', [OrderController::class, 'store'])->middleware('throttle:checkout')->name('order.store');
    Route::get('/spasibo', [OrderController::class, 'thankYou'])->name('thank-you');

    Route::get('/korzina', [CartController::class, 'index'])->name('cart');
    Route::post('/korzina/dobavit/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::match(['POST', 'DELETE'], '/korzina/udalit/{productId}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/izbrannoe', [FavoritesController::class, 'index'])->name('favorites');
    Route::post('/izbrannoe/dobavit/{product}', [FavoritesController::class, 'add'])->name('favorites.add');
    Route::match(['POST', 'DELETE'], '/izbrannoe/udalit/{productId}', [FavoritesController::class, 'remove'])->name('favorites.remove');

    Route::get('/sravnenie', [CompareController::class, 'index'])->name('compare');
    Route::post('/sravnenie/dobavit/{productId}', [CompareController::class, 'add'])->name('compare.add');
    Route::match(['POST', 'DELETE'], '/sravnenie/udalit/{productId}', [CompareController::class, 'remove'])->name('compare.remove');

    Route::post('/callback', [StaticPageController::class, 'callback'])->middleware('throttle:callback');
    Route::post('/checkout', [OrderController::class, 'store'])->middleware('throttle:checkout');
    Route::post('/cart/add/{product}', [CartController::class, 'add']);
    Route::match(['POST', 'DELETE'], '/cart/remove/{productId}', [CartController::class, 'remove']);
    Route::post('/favorites/add/{product}', [FavoritesController::class, 'add']);
    Route::match(['POST', 'DELETE'], '/favorites/remove/{productId}', [FavoritesController::class, 'remove']);
    Route::post('/compare/add/{productId}', [CompareController::class, 'add']);
    Route::match(['POST', 'DELETE'], '/compare/remove/{productId}', [CompareController::class, 'remove']);
});

Route::middleware('auth')->group(function () {
    Route::get('/kabinet', [AuthController::class, 'profile'])->name('profile');
    Route::put('/kabinet', [AuthController::class, 'updateProfile'])->name('profile.update');
});

Route::get('/vhod', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/vhod', [AuthController::class, 'login'])->middleware('throttle:login');

Route::get('/registraciya', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/registraciya', [AuthController::class, 'register']);

Route::post('/vyhod', [AuthController::class, 'logout'])->name('logout');
Route::post('/logout', [AuthController::class, 'logout']);

Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');

    Route::get('/products', [AdminProductController::class, 'index'])->name('products');
    Route::get('/products/export/{format}', [AdminProductController::class, 'export'])->name('products.export');
    Route::post('/products/import', [AdminProductController::class, 'import'])->name('products.import');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update.status');

    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::resource('slides', SlideController::class)->except(['show']);

    Route::get('/portfolio', [AdminPortfolioController::class, 'index'])->name('portfolio.index');
    Route::get('/portfolio/create', [AdminPortfolioController::class, 'create'])->name('portfolio.create');
    Route::post('/portfolio', [AdminPortfolioController::class, 'store'])->name('portfolio.store');
    Route::get('/portfolio/{portfolioItem}/edit', [AdminPortfolioController::class, 'edit'])->name('portfolio.edit');
    Route::put('/portfolio/{portfolioItem}', [AdminPortfolioController::class, 'update'])->name('portfolio.update');
    Route::delete('/portfolio/{portfolioItem}', [AdminPortfolioController::class, 'destroy'])->name('portfolio.destroy');
});
