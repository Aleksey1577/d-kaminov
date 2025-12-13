<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SitemapController extends Controller
{
    public function __invoke(): StreamedResponse
    {
        $static = [
            route('home'),
            route('catalog'),
            route('contacts'),
            route('portfolio'),
            route('montage'),
            route('delivery'),
        ];

        return response()->stream(function () use ($static) {
            $writeUrl = static function (string $loc, ?string $lastmod = null): void {
                $locEsc = htmlspecialchars($loc, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                echo "  <url>\n";
                echo "    <loc>{$locEsc}</loc>\n";
                if ($lastmod) {
                    $lastmodEsc = htmlspecialchars($lastmod, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    echo "    <lastmod>{$lastmodEsc}</lastmod>\n";
                }
                echo "  </url>\n";
            };

            echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
            echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

            foreach ($static as $url) {
                $writeUrl($url);
            }

            $knownCategoryNames = [];
            foreach (Category::query()->select(['name', 'slug', 'updated_at'])->orderBy('id')->cursor() as $category) {
                $knownCategoryNames[(string) $category->name] = true;
                $writeUrl(
                    route('catalog', ['category' => $category->slug]),
                    optional($category->updated_at)->toAtomString(),
                );
            }

            foreach (Product::query()
                ->select(['kategoriya'])
                ->whereNotNull('kategoriya')
                ->where('kategoriya', '!=', '')
                ->distinct()
                ->orderBy('kategoriya')
                ->cursor() as $row) {
                $name = (string) $row->kategoriya;
                if ($name === '' || isset($knownCategoryNames[$name])) {
                    continue;
                }
                $writeUrl(route('catalog', ['category' => $name]));
            }

            foreach (Product::query()->select(['product_id', 'naimenovanie', 'updated_at'])->latest('updated_at')->cursor() as $product) {
                $writeUrl(
                    route('product', ['slug' => $product->slug]),
                    optional($product->updated_at)->toAtomString(),
                );
            }

            echo "</urlset>\n";
        }, 200, ['Content-Type' => 'application/xml']);
    }
}
