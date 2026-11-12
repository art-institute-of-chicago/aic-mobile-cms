<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTourContent;
use Tests\MockApi;
use Tests\TestCase;

class DashboardFeedTest extends TestCase
{
    use CreatesTourContent;
    use MockApi;
    use RefreshDatabase;

    public function test_lists_published_visible_featured_tours_by_position(): void
    {
        $second = $this->createTour(['featured' => true, 'position' => 2]);
        $first = $this->createTour(['featured' => true, 'position' => 1]);
        $this->createTour(['featured' => false]);
        $this->createTour(['featured' => true, 'published' => false]);
        $this->createTour(['featured' => true, 'publish_end_date' => now()->subDay()]);

        $this->getJson('/api/dashboard')
            ->assertOk()
            ->assertExactJson(['dashboard' => [
                'featured_tours' => [(string) $first->id, (string) $second->id],
                'featured_exhibitions' => [],
            ]]);
    }
}
