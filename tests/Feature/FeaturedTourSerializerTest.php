<?php

namespace Tests\Feature;

use App\Models\Tour;
use App\Repositories\Serializers\FeaturedTourSerializer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeaturedTourSerializerTest extends TestCase
{
    use RefreshDatabase;

    public function test_serialize(): void
    {
        $serializer = new FeaturedTourSerializer();
        Tour::factory()->count(3)->sequence(['featured' => true], ['featured' => false])->create();
        $featuredTours = Tour::featured()->get();
        $serialized = $serializer->serialize($featuredTours);

        $this->assertArrayHasKey('featured_tours', $serialized);
        $this->assertCount($featuredTours->count(), $serialized['featured_tours']);
        foreach ($serialized['featured_tours'] as $id) {
            $this->assertIsString($id);
            $this->assertContains((int) $id, $featuredTours->pluck('id'));
        }
    }
}
