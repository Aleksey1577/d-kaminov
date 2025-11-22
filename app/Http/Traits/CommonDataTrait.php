<?php

namespace App\Http\Traits;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Session; 
use Illuminate\Support\Facades\Log;  

trait CommonDataTrait
{
    /**
     * @return SupportCollection<int, array{name: string, image_url: string|null}>
     */
    protected function getCategories(): SupportCollection
    {
        return Product::select('kategoriya')
            ->distinct()
            ->get()
            ->map(fn($item) => [
                'name' => $item->kategoriya,
                'image_url' => Product::where('kategoriya', $item->kategoriya)
                    ->value('image_url'),
            ]);
    }

    protected function getCartQuantity(): int
    {
        $cart = Session::get('cart', []);
        return collect($cart)->sum('quantity') ?? 0;
    }

    /**
     * @return int
     */
    protected function getUserCompareCount(): int
    {
        $compare = Session::get('compare', []);
        return count($compare);  
    }
    
    /**
     * @return int
     */
    protected function getUserFavoritesCount(): int
    {
        $favorites = Session::get('favorites', []);
        return count($favorites);  
    }

    /**
     * Устанавливает отображаемую цену для продуктов (минимальную из вариантов для 'product').
     *
     * @param Collection|LengthAwarePaginator $products
     * @return void
     */
    protected function setDisplayPrices(Collection|LengthAwarePaginator $products): void
    {
        $collection = $products instanceof LengthAwarePaginator ? $products->getCollection() : $products;
        $collection->transform(function ($product) {
            if ($product->tip_stroki === 'product') {
                // Дебаг: Проверим, сколько вариантов найдено
                $variantsCount = Product::where('tip_stroki', 'variant')
                    ->where('naimenovanie', $product->naimenovanie)
                    ->count();
                Log::info("Product '{$product->naimenovanie}' variants count: {$variantsCount}");

                $minPrice = Product::where('tip_stroki', 'variant')
                    ->where('naimenovanie', $product->naimenovanie)
                    ->min('price');
                $product->display_price = $minPrice ?? 0;  // Fallback на 0, если нет вариантов
            } elseif ($product->tip_stroki === 'product_variant') {
                $product->display_price = $product->price ?? 0;
            } else {
                $product->display_price = $product->price ?? 0;  // Fallback для других типов
            }
            return $product;
        });
    }
}