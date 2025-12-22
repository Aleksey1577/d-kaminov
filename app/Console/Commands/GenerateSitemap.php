<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate sitemap.xml';

    public function handle()
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/'))
            ->add(Url::create('/catalog'))
            ->add(Url::create('/kontakty'))
            ->add(Url::create('/raboty'))
            ->add(Url::create('/montazh'))
            ->add(Url::create('/dostavka'));

        Product::query()
            ->whereIn('tip_stroki', ['product', 'product_variant', 'variant'])
            ->orderBy('product_id')
            ->chunk(500, function ($products) use ($sitemap) {
                foreach ($products as $product) {
                    $sitemap->add(
                        Url::create(route('product', $product->slug))
                            ->setLastModificationDate($product->updated_at ?? now())
                    );
                }
            });

        $categories = Product::query()
            ->whereIn('tip_stroki', ['product', 'product_variant', 'variant'])
            ->whereNotNull('kategoriya')
            ->where('kategoriya', '!=', '')
            ->distinct()
            ->orderBy('kategoriya')
            ->pluck('kategoriya');

        foreach ($categories as $category) {
            $sitemap->add(
                Url::create(route('catalog', ['category' => Str::slug($category)]))
            );
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('sitemap.xml создан!');
    }
}
