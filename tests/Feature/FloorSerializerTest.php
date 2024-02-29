<?php

namespace Tests\Feature;

use App\Models\Floor;
use App\Repositories\Serializers\FloorSerializer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FloorSerializerTest extends TestCase
{
    use RefreshDatabase;

    public function test_serialize(): void
    {
        $floors = Floor::factory()->count(count(Floor::LEVELS))->make();
        $serializer = new FloorSerializer();
        $serialized = $serializer->serialize($floors);

        $this->assertArrayHasKey('map_floors', $serialized);
        foreach ($serialized['map_floors'] as $id => $floor) {
            $this->assertStringStartsWith('map_floor', $id);
            if ($floor['label'] == 'LL') {
                $this->assertStringEndsWith(
                    '0',
                    $id,
                    'For backward compatibility, the key for level LL must end in "0"'
                );
            } else {
                $this->assertStringEndsWith($floor['label'], $id);
            }
            $this->assertArrayHasKey('label', $floor);
            $this->assertArrayHasKey('floor_plan', $floor);
            $this->assertArrayHasKey('anchor_pixel_1', $floor);
            $this->assertArrayHasKey('anchor_pixel_2', $floor);
            $this->assertArrayHasKey('anchor_location_1', $floor);
            $this->assertArrayHasKey('anchor_location_2', $floor);
            $this->assertArrayHasKey('tile_images', $floor);
        }
    }

    public function test_floor_plan_url(): void
    {
        $floor = Floor::factory()->withFloorPlan()->create();
        $serializer = new FloorSerializer();
        $serialized = $serializer->serialize([$floor]);
        $floorPlanUrl = $serialized['map_floors']['map_floor' . ($floor->level == 'LL' ? '0' : $floor->level)]['floor_plan'];
        $this->assertStringStartsWith('https://', $floorPlanUrl, 'The floor plan url uses the https scheme');
    }
}
