<?php

namespace Tests\Feature;

use App\Models\LoanObject;
use App\Models\Selector;
use App\Models\Stop;
use App\Models\Tour;
use App\Repositories\Serializers\TourSerializer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TourSerializerTest extends TestCase
{
    use RefreshDatabase;

    public function test_serialize(): void
    {
        $serializer = new TourSerializer();
        $tours = Tour::factory()->count(3)->has(Selector::factory())->create();
        $serialized = $serializer->serialize($tours);

        $this->assertArrayHasKey('tours', $serialized);
        $this->assertCount(3, $serialized['tours']);
        foreach ($serialized['tours'] as $id => $tour) {
            $this->assertArrayHasKey('title', $tour);
            $this->assertArrayHasKey('translations', $tour);
            $this->assertArrayHasKey('location', $tour);
            $this->assertArrayHasKey('latitude', $tour);
            $this->assertArrayHasKey('longitude', $tour);
            $this->assertArrayHasKey('floor', $tour);
            $this->assertArrayHasKey('image_url', $tour);
            $this->assertArrayHasKey('thumbnail_full_path', $tour);
            $this->assertArrayHasKey('large_image_full_path', $tour);
            $this->assertArrayHasKey('selector_number', $tour);
            $this->assertIsString($tour['selector_number']);
            $this->assertArrayHasKey('description', $tour);
            $this->assertArrayHasKey('intro', $tour);
            $this->assertArrayHasKey('tour_duration', $tour);
            $this->assertArrayHasKey('tour_audio', $tour);
            $this->assertArrayHasKey('weight', $tour);
            $this->assertArrayHasKey('tour_stops', $tour);
        }
    }

    public function test_only_stops_for_on_view_objects_are_included(): void
    {
        $onViewObject = LoanObject::factory()->create(['is_on_view' => true]);
        $offViewObject = LoanObject::factory()->create(['is_on_view' => false]);
        $stops = Stop::factory()->count(2)->has(Selector::factory())->create();
        $stops->first()->selector->object()->associate($onViewObject)->save();
        $stops->last()->selector->object()->associate($offViewObject)->save();
        $tour = Tour::factory()->create();
        $tour->stops()->attach($stops);
        $serializer = new TourSerializer();
        $serialized = $serializer->serialize([$tour]);
        $this->assertCount(1, $serialized['tours'][0]['tour_stops']);
    }
}
