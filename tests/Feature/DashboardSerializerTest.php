<?php

namespace Tests\Feature;

use App\Models\Tour;
use App\Repositories\Serializers\DashboardSerializer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardSerializerTest extends TestCase
{
    use RefreshDatabase;

    public function test_serialize(): void
    {
        $serializer = new DashboardSerializer();
        $featuredTours = Tour::factory(['featured' => true])->count(2)->create();
        $serialized = $serializer->serialize($featuredTours);

        $this->assertArrayHasKey('dashboard', $serialized);
        $this->assertArrayHasKey('featured_tours', $serialized['dashboard']);
        $this->assertNotEmpty($serialized['dashboard']['featured_tours']);
        $this->assertArrayHasKey('featured_exhibitions', $serialized['dashboard']);
        $this->assertEmpty($serialized['dashboard']['featured_exhibitions']);
    }
}
