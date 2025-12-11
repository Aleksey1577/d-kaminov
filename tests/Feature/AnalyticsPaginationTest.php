<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsPaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_analytics_paginates_hundred_per_page(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        Visit::factory()->count(120)->create();

        $response = $this->actingAs($admin)->get(route('admin.analytics'));

        $response->assertOk();
        $paginator = $response->viewData('visits');

        $this->assertNotNull($paginator);
        $this->assertEquals(100, $paginator->perPage());
        $this->assertCount(100, $paginator);
    }
}
