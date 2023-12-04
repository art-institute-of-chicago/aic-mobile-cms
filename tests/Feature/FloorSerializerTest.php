<?php

namespace Tests\Feature;

use App\Models\Floor;
use App\Repositories\Serializers\FloorSerializer;
use Tests\TestCase;

class FloorSerializerTest extends TestCase
{
    public function test_serialize(): void
    {
        $floors = Floor::factory()->count(count(Floor::LEVELS))->make();
        $serializer = new FloorSerializer();
        $serialized = $serializer->serialize($floors);

        $this->assertArrayHasKey('map_floors', $serialized);
        foreach ($serialized['map_floors'] as $id => $floor) {
            $this->assertStringStartsWith('map_floor', $id);
            $this->assertStringEndsWith($floor['label'], $id);
            $this->assertArrayHasKey('label', $floor);
            $this->assertArrayHasKey('floor_plan', $floor);
            $this->assertArrayHasKey('anchor_pixel_1', $floor);
            $this->assertArrayHasKey('anchor_pixel_2', $floor);
            $this->assertArrayHasKey('anchor_location_1', $floor);
            $this->assertArrayHasKey('anchor_location_2', $floor);
            $this->assertArrayHasKey('tile_images', $floor);
        }
    }
}
