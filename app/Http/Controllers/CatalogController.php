<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Traits\CommonDataTrait;
use App\Services\SeoService;

class CatalogController extends Controller
{
    use CommonDataTrait;

    public function index(Request $request)
    {
        $perPage  = 12;
        $category = $request->string('category')->trim()->toString();
        $seo      = app(SeoService::class);

        // БАЗА: нужные строки
        $base = Product::whereIn('tip_stroki', ['product', 'product_variant', 'variant'])
            ->when($category !== '', fn($q) => $q->where('kategoriya', $category));

        // ===== РЕЖИМ 1: Плитка категорий, если категория НЕ выбрана =====
        if ($category === '') {
            $seo->fill([
                'title'       => 'Каталог каминов и печей | D-Kaminov',
                'description' => 'Каталог каминов, топок, печей и аксессуаров в D-Kaminov. Фильтры по цене, бренду и наличию, доставка и монтаж под ключ.',
            ])->canonical()->breadcrumb('Главная', route('home'))->breadcrumb('Каталог', route('catalog'));

            // Получаем список категорий из товаров (distinct по 'kategoriya')
            $rawCategories = Product::query()
                ->whereIn('tip_stroki', ['product', 'product_variant', 'variant'])
                ->whereNotNull('kategoriya')
                ->where('kategoriya', '!=', '')
                ->distinct()
                ->orderBy('kategoriya')
                ->pluck('kategoriya');

            // Карта статичных картинок (как у тебя на главной)
            $categoryImages = [
                'Биокамины' => '/assets/category/36994.970.png',
                'Электроочаги' => '/assets/category/e2lospvm8mvty3ne003sjt6ykq7m644l.jpg',
                'Порталы' => '/assets/category/portal.jpg',
                'Каминокомплекты' => '/assets/category/umc7hdqesslii0i4d5gh362l9vvvmz43.jpg',
                'Топки' => '/assets/category/topki.png',
                'Печи-камины' => '/assets/category/kamin.jpg',
                'Газовые топки, уличные нагреватели' => '/assets/category/gazkamin.png',
                'Дымоходы' => '/assets/category/dimohod.png',
                'Вентиляция' => '/assets/category/ventilresh.png',
            ];

            // Преобразуем к массиву для вьюхи
            $categories = $rawCategories->map(function ($name) use ($categoryImages) {
                return [
                    'name' => $name,
                    'image_url' => $categoryImages[$name] ?? 'images/placeholder.png',
                ];
            });

            // Пустые заглушки для include-ов
            $products = collect();
            $proizvoditeli = collect();
            $v_nalichii_options = [];
            $filterOptions = [];
            $categoryFilters = [];

            return view('catalog', compact(
                'products',
                'proizvoditeli',
                'v_nalichii_options',
                'filterOptions',
                'category',
                'categoryFilters',
                'categories'
            ))->with('showCategories', true)->with('seo', $seo);
        }

        // ===== РЕЖИМ 2: Выбрана категория — фильтры + товары =====
        $seo->fill([
            'title'       => 'Купить ' . $category . ' | D-Kaminov',
            'description' => 'Купить ' . $category . ' в Самаре и с доставкой по России. Цены, наличие, фильтры и характеристики в каталоге D-Kaminov.',
        ])->canonical()->breadcrumb('Главная', route('home'))->breadcrumb('Каталог', route('catalog'))->breadcrumb($category);

        $proizvoditeli = (clone $base)->distinct()->pluck('proizvoditel')->filter()->values();
        $v_nalichii_options = (clone $base)->distinct()->pluck('v_nalichii_na_sklade')->filter()->values();

        $categoryFilters = [
            'Биокамины' => ['tip_tovara', 'obem_zalivaemogo_topliva'],
            'Каминное/печное литье' => ['tip_tovara', 'material'],
            'Газовые топки, уличные нагреватели' => ['diametr_dymokhoda', 'tip_gaza', 'tip_ustroystva'],
            'Каминокомплекты' => ['vysota', 'shirina', 'glubina'],
            'Вентиляция' => ['tip_tovara'],
            'Электроочаги' => ['tip_ochaga', 'pult_du'],
            'Печи, камины, каминокомплекты' => ['diametr_dymokhoda', 'prisoyedinenie_dymokhoda', 'forma_stekla_i_dverey', 'moshchnost', 'sposob_otkrytiya_dvertsy'],
        ];

        $filterOptions = [];
        if (isset($categoryFilters[$category])) {
            foreach ($categoryFilters[$category] as $field) {
                $filterOptions[$field] = (clone $base)->distinct()->pluck($field)->filter()->values();
            }
        }

        $productsQuery = (clone $base)
            ->when($request->search, fn($q, $s) => $q->where('naimenovanie', 'like', "%{$s}%"))
            ->when($request->price_min, fn($q, $min) => $q->where('price', '>=', $min))
            ->when($request->price_max, fn($q, $max) => $q->where('price', '<=', $max))
            ->when($request->proizvoditel, fn($q, $p) => $q->where('proizvoditel', $p))
            ->when($request->v_nalichii, fn($q, $v) => $q->where('v_nalichii_na_sklade', $v));

        $simpleFilters = [
            'tip_tovara',
            'obem_zalivaemogo_topliva',
            'material',
            'diametr_dymokhoda',
            'tip_gaza',
            'tip_ustroystva',
            'tip_ochaga',
            'pult_du',
            'prisoyedinenie_dymokhoda',
            'forma_stekla_i_dverey',
            'sposob_otkrytiya_dvertsy',
        ];
        foreach ($simpleFilters as $field) {
            if ($request->filled($field)) {
                $productsQuery->where($field, $request->$field);
            }
        }

        $rangeFilters = [
            'vysota' => ['vysota_min', 'vysota_max'],
            'shirina' => ['shirina_min', 'shirina_max'],
            'glubina' => ['glubina_min', 'glubina_max'],
            'moshchnost' => ['moshchnost_min', 'moshchnost_max'],
        ];
        foreach ($rangeFilters as $field => [$minField, $maxField]) {
            if ($request->filled($minField)) $productsQuery->where($field, '>=', $request->$minField);
            if ($request->filled($maxField)) $productsQuery->where($field, '<=', $request->$maxField);
        }

        $products = $productsQuery->orderBy('naimenovanie')->paginate($perPage)->withQueryString();
        $this->setDisplayPrices($products);

        // categories пригодятся для хлебных крошек/сайдбаров (необязательно)
        $categories = collect();

        return view('catalog', compact(
            'products',
            'proizvoditeli',
            'v_nalichii_options',
            'filterOptions',
            'category',
            'categoryFilters',
            'categories'
        ))->with('showCategories', false)->with('seo', $seo);
    }


    public function search(Request $request)
    {
        $base = Product::whereIn('tip_stroki', ['product', 'product_variant', 'variant']);
        $seo  = app(SeoService::class);
        $query = trim((string) $request->search);
        $seo->fill([
            'title'       => $query ? 'Поиск: ' . $query . ' | D-Kaminov' : 'Поиск по каталогу | D-Kaminov',
            'description' => $query
                ? 'Результаты поиска по запросу "' . $query . '" в каталоге каминов, топок и печей D-Kaminov.'
                : 'Поиск по каталогу каминов, топок, печей и аксессуаров в D-Kaminov.',
        ])->canonical()->breadcrumb('Главная', route('home'))->breadcrumb('Поиск');

        $productsQuery = (clone $base)
            ->when($request->search, function ($q, $s) {
                return $q->where('naimenovanie', 'like', "%{$s}%")
                    ->orWhere('sku', 'like', "%{$s}%");
            })
            ->when($request->price_min, fn($q, $min) => $q->where('price', '>=', $min))
            ->when($request->price_max, fn($q, $max) => $q->where('price', '<=', $max));

        $products = $productsQuery->orderBy('naimenovanie')->paginate(12)->withQueryString();
        $this->setDisplayPrices($products);

        return view('search', compact('products'))->with('seo', $seo);
    }
}
