<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
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
            ->add(Url::create('/contacts'))
            ->add(Url::create('/portfolio'))
            ->add(Url::create('/montage'))
            ->add(Url::create('/delivery'));

        // Динамические страницы товаров
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

        // Категории каталога через параметр category
        $categories = Product::query()
            ->whereIn('tip_stroki', ['product', 'product_variant', 'variant'])
            ->whereNotNull('kategoriya')
            ->where('kategoriya', '!=', '')
            ->distinct()
            ->orderBy('kategoriya')
            ->pluck('kategoriya');

        foreach ($categories as $category) {
            $sitemap->add(
                Url::create(
                    route('catalog') . '?' . http_build_query(['category' => $category])
                )
            );
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('sitemap.xml создан!');
    }
}
