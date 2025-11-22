<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Traits\CommonDataTrait;

class CatalogController extends Controller
{
    use CommonDataTrait;

    public function index(Request $request)
    {
        $perPage = 12;
        $category = $request->category;

        $base = Product::whereIn('tip_stroki', ['product', 'product_variant'])
            ->when($category, fn($q) => $q->where('kategoriya', $category));

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
            'tip_tovara', 'obem_zalivaemogo_topliva', 'material', 'diametr_dymokhoda',
            'tip_gaza', 'tip_ustroystva', 'tip_ochaga', 'pult_du',
            'prisoyedinenie_dymokhoda', 'forma_stekla_i_dverey', 'sposob_otkrytiya_dvertsy',
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
            if ($request->filled($minField)) {
                $productsQuery->where($field, '>=', $request->$minField);
            }
            if ($request->filled($maxField)) {
                $productsQuery->where($field, '<=', $request->$maxField);
            }
        }

        $products = $productsQuery->orderBy('naimenovanie')->paginate($perPage)->withQueryString();
        $this->setDisplayPrices($products);

        return view('catalog', compact(
            'products', 'proizvoditeli', 'v_nalichii_options', 'filterOptions', 'category', 'categoryFilters'
        ));
    }

    public function search(Request $request)
    {
        $base = Product::whereIn('tip_stroki', ['product', 'product_variant']);

        $productsQuery = (clone $base)
            ->when($request->search, function ($q, $s) {
                return $q->where('naimenovanie', 'like', "%{$s}%")
                    ->orWhere('sku', 'like', "%{$s}%");
            })
            ->when($request->price_min, fn($q, $min) => $q->where('price', '>=', $min))
            ->when($request->price_max, fn($q, $max) => $q->where('price', '<=', $max));

        $products = $productsQuery->orderBy('naimenovanie')->paginate(12)->withQueryString();
        $this->setDisplayPrices($products);

        return view('search', compact('products'));
    }
}