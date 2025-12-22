<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Traits\CommonDataTrait;
use App\Services\SeoService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CatalogController extends Controller
{
    use CommonDataTrait;

    protected function applySort(Builder $query, string $sort): void
    {
        $sort = trim($sort);
        $table = (new Product())->getTable();

        $variantMinPriceSql = "(select min(v.price) from {$table} as v where v.tip_stroki = 'variant' and v.naimenovanie = {$table}.naimenovanie)";
        $sortPriceSql = "(case when {$table}.tip_stroki = 'product' then {$variantMinPriceSql} else {$table}.price end)";

        match ($sort) {
            'price_asc' => $query->orderByRaw("({$sortPriceSql} is null) asc, {$sortPriceSql} asc")
                ->orderBy('naimenovanie'),
            'price_desc' => $query->orderByRaw("({$sortPriceSql} is null) asc, {$sortPriceSql} desc")
                ->orderBy('naimenovanie'),
            'new_desc' => $query->orderByDesc('created_at')->orderBy('naimenovanie'),
            'new_asc' => $query->orderBy('created_at')->orderBy('naimenovanie'),
            default => $query->orderBy('naimenovanie'),
        };
    }

    public function index(Request $request)
    {
        $perPage  = 12;
        $routeCategory = $request->route('category');
        $queryCategory = $request->string('category')->trim()->toString();
        $categoryParam = $routeCategory !== null && $routeCategory !== '' ? $routeCategory : $queryCategory;
        $seo      = app(SeoService::class);

        $category = '';
        $categorySlug = null;

        if ($categoryParam !== '') {
            $bySlug = Category::query()->where('slug', $categoryParam)->first();
            if ($bySlug) {
                $category = $bySlug->name;
                $categorySlug = $bySlug->slug;
            } else {
                $byName = Category::query()->where('name', $categoryParam)->first();
                if ($byName) {
                    $category = $byName->name;
                    $categorySlug = $byName->slug;
                } else {
                    $categoryNames = Product::query()
                        ->whereIn('tip_stroki', ['product', 'product_variant', 'variant'])
                        ->whereNotNull('kategoriya')
                        ->where('kategoriya', '!=', '')
                        ->distinct()
                        ->orderBy('kategoriya')
                        ->pluck('kategoriya');

                    $directMatch = $categoryNames->first(fn($name) => (string) $name === $categoryParam);
                    if ($directMatch) {
                        $category = (string) $directMatch;
                        $categorySlug = Str::slug($category);
                    } else {
                        $slugMatch = $categoryNames->first(fn($name) => Str::slug((string) $name) === $categoryParam);
                        if ($slugMatch) {
                            $category = (string) $slugMatch;
                            $categorySlug = Str::slug($category);
                        } else {
                            $category = $categoryParam;
                            $categorySlug = Str::slug($categoryParam);
                        }
                    }
                }
            }

            $needsRedirect = $routeCategory === null || $routeCategory === '' || $categorySlug !== $routeCategory || $queryCategory !== '';
            if ($categorySlug && $needsRedirect) {
                $query = $request->query();
                unset($query['category']);

                return redirect()
                    ->route('catalog', ['category' => $categorySlug] + $query, 301);
            }
        }

        $base = Product::whereIn('tip_stroki', ['product', 'product_variant', 'variant'])
            ->when($category !== '', fn($q) => $q->where('kategoriya', $category));

        if ($category === '') {
            $seo->forPage('catalog')
                ->canonical(route('catalog'))
                ->breadcrumb('Главная', route('home'))
                ->breadcrumb('Каталог', route('catalog'));

            $rawCategories = Product::query()
                ->whereIn('tip_stroki', ['product', 'product_variant', 'variant'])
                ->whereNotNull('kategoriya')
                ->where('kategoriya', '!=', '')
                ->distinct()
                ->orderBy('kategoriya')
                ->pluck('kategoriya');

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

            $categories = $rawCategories->map(function ($name) use ($categoryImages) {
                return [
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'image_url' => $categoryImages[$name] ?? 'assets/placeholder.png',
                ];
            });

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
                'categorySlug',
                'categoryFilters',
                'categories'
            ))->with('showCategories', true)->with('seo', $seo);
        }

        $categoryKeywords = implode(', ', array_filter([
            $category . ' купить',
            $category . ' Самара',
            'каталог ' . $category,
            $category . ' цены',
            'камины Самара',
            'дом каминов',
            'дом каминов Самара',
            'Дом каминов',
        ]));

        $seo->fill([
            'title'       => 'Купить ' . $category . ' в Дом каминов',
            'description' => 'Купить ' . $category . ' в Дом каминов в Самаре и с доставкой по России. Цены, наличие, фильтры и характеристики.',
            'keywords'    => $categoryKeywords,
        ])->canonical(route('catalog', ['category' => $categorySlug]))
            ->breadcrumb('Главная', route('home'))
            ->breadcrumb('Каталог', route('catalog'))
            ->breadcrumb($category);

        $queryWithoutCategoryAndPage = collect($request->query())->except(['category', 'page'])->filter(function ($value) {
            if (is_array($value)) {
                return !empty($value);
            }
            return $value !== null && $value !== '';
        });
        if ($request->integer('page', 1) > 1 || $queryWithoutCategoryAndPage->isNotEmpty()) {
            $seo->robots('noindex,follow');
        }

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

	        $this->applySort($productsQuery, (string) $request->query('sort', ''));
	        $products = $productsQuery->paginate($perPage)->withQueryString();
	        $this->setDisplayPrices($products);

        $itemList = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'itemListElement' => [],
        ];
        foreach ($products as $i => $product) {
            $itemList['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => (($products->currentPage() - 1) * $products->perPage()) + $i + 1,
                'item' => route('product', ['slug' => $product->slug]),
            ];
        }
        if (!empty($itemList['itemListElement'])) {
            $seo->pushJsonLd($itemList);
        }

        $categories = collect();

        return view('catalog', compact(
            'products',
            'proizvoditeli',
            'v_nalichii_options',
            'filterOptions',
            'category',
            'categorySlug',
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
            'title'       => $query ? 'Поиск: ' . $query . ' | Дом каминов' : 'Поиск по каталогу | Дом каминов',
            'description' => $query
                ? 'Результаты поиска по запросу "' . $query . '" в каталоге каминов, топок и печей Дом каминов.'
                : 'Поиск по каталогу каминов, топок, печей и аксессуаров в Дом каминов.',
        ])->robots('noindex,follow')
            ->canonical()
            ->breadcrumb('Главная', route('home'))
            ->breadcrumb('Поиск');

        $productsQuery = (clone $base)
            ->when($request->search, function ($q, $s) {
                return $q->where(function ($inner) use ($s) {
                    $inner->where('naimenovanie', 'like', "%{$s}%")
                        ->orWhere('sku', 'like', "%{$s}%");
                });
            })
	            ->when($request->price_min, fn($q, $min) => $q->where('price', '>=', $min))
	            ->when($request->price_max, fn($q, $max) => $q->where('price', '<=', $max));

	        $this->applySort($productsQuery, (string) $request->query('sort', ''));
	        $products = $productsQuery->paginate(12)->withQueryString();
	        $this->setDisplayPrices($products);

	        return view('search', compact('products'))->with('seo', $seo);
    }
}
