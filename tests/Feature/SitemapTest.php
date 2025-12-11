<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_renders_static_and_dynamic_urls(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['naimenovanie' => 'Тестовый товар']);

        $response = $this->get(route('sitemap'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
        $response->assertSee(route('home'), false);
        $response->assertSee(route('catalog', ['category' => $category->slug]), false);
        $response->assertSee(route('product', ['slug' => $product->slug]), false);
    }
}
