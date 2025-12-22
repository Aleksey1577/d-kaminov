<?php

namespace App\Http\Traits;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

trait CommonDataTrait
{

    protected function getCategories(): SupportCollection
    {
        $data = Cache::remember('categories:list:v2', now()->addHours(6), function (): array {
            return Product::query()
                ->whereNotNull('kategoriya')
                ->where('kategoriya', '!=', '')
                ->selectRaw('kategoriya as name, MIN(image_url) as image_url')
                ->groupBy('kategoriya')
                ->orderBy('kategoriya')
                ->get()
                ->map(fn($row) => [
                    'name' => (string) $row->name,
                    'slug' => Str::slug((string) $row->name),
                    'image_url' => $row->image_url ? (string) $row->image_url : null,
                ])
                ->values()
                ->all();
        });

        return collect($data);
    }

    protected function getCartQuantity(): int
    {
        $cart = Session::get('cart', []);
        return collect($cart)->sum('quantity') ?? 0;
    }

    protected function getUserCompareCount(): int
    {
        $compare = Session::get('compare', []);
        return count($compare);
    }

    protected function getUserFavoritesCount(): int
    {
        $favorites = Session::get('favorites', []);
        return count($favorites);
    }

    protected function setDisplayPrices(Collection|LengthAwarePaginator $products): void
    {
        $collection = $products instanceof LengthAwarePaginator ? $products->getCollection() : $products;
        $names = $collection
            ->filter(fn($product) => ($product->tip_stroki ?? null) === 'product')
            ->pluck('naimenovanie')
            ->filter(fn($v) => is_string($v) && trim($v) !== '')
            ->map(fn($v) => trim($v))
            ->unique()
            ->values();

        $minPricesByName = $names->isEmpty()
            ? collect()
            : Product::query()
                ->where('tip_stroki', 'variant')
                ->whereIn('naimenovanie', $names->all())
                ->selectRaw('naimenovanie, MIN(price) as min_price')
                ->groupBy('naimenovanie')
                ->pluck('min_price', 'naimenovanie');

        $collection->transform(function ($product) use ($minPricesByName) {
            $type = $product->tip_stroki ?? null;

            if ($type === 'product') {
                $name = trim((string) ($product->naimenovanie ?? ''));
                $product->display_price = (float) ($minPricesByName[$name] ?? 0);
                return $product;
            }

            $product->display_price = (float) ($product->price ?? 0);
            return $product;
        });
    }
}
