<?php

namespace Tests\Feature\Api;

use App\Models\Annotation;
use App\Models\AnnotationCategory;
use App\Models\AnnotationType;
use App\Models\Floor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\MockApi;
use Tests\TestCase;

class MapFeedsTest extends TestCase
{
    use MockApi;
    use RefreshDatabase;

    public function test_map_floors_output(): void
    {
        Floor::factory()->create(['level' => 'LL', 'geo_id' => '0002', 'title' => 'Lower Level']);
        Floor::factory()->create(['level' => '1', 'geo_id' => '0003', 'title' => 'First Floor']);

        $floor = fn (string $label) => [
            'label' => $label,
            'floor_plan' => null,
            'anchor_pixel_1' => '855.955,1338.365',
            'anchor_pixel_2' => '1011.94,1338.365',
            'anchor_location_1' => '41.88002009571711,-87.62398928403854',
            'anchor_location_2' => '41.8800240897643,-87.62334823608397',
            'tile_images' => null,
        ];
        $this->getJson('/api/map_floors')
            ->assertOk()
            ->assertExactJson(['map_floors' => [
                'map_floor0' => $floor('LL'),
                'map_floor1' => $floor('1'),
            ]]);
    }

    public function test_floor_plan_url(): void
    {
        $floor = Floor::factory()->withFloorPlan()->create(['level' => '2', 'geo_id' => '0004']);
        $uuid = $floor->files()->first()->uuid;

        $this->getJson('/api/map_floors')
            ->assertOk()
            ->assertJsonPath('map_floors.map_floor2.floor_plan', "https://localhost/storage/uploads/$uuid");
    }

    public function test_map_annotation_output(): void
    {
        $floor = Floor::factory()->create(['level' => '1', 'geo_id' => '0003', 'title' => 'First Floor']);
        $amenity = AnnotationType::factory()
            ->for(AnnotationCategory::factory(['title' => 'Amenity']), 'category')
            ->create(['title' => 'Restroom']);
        $annotation = Annotation::factory()->for($floor)->create([
            'label' => 'North',
            'description' => 'Near the entrance.',
            'latitude' => 41.8796,
            'longitude' => -87.6237,
        ]);
        $annotation->types()->attach($amenity);
        $nid = $this->cantorPair($annotation->id, $amenity->id);

        $this->getJson('/api/map_annotations')
            ->assertOk()
            ->assertExactJson(['map_annontations' => [$nid => [
                'title' => 'First Floor - Restroom - North',
                'status' => '1',
                'nid' => $nid,
                'type' => 'map_annotation',
                'translations' => [],
                'location' => '41.8796000000000,-87.6237000000000',
                'latitude' => '41.8796000000000',
                'longitude' => '-87.6237000000000',
                'floor' => '1',
                'description' => 'Near the entrance.',
                'label' => 'North',
                'annotation_type' => 'Amenity',
                'text_type' => null,
                'amenity_type' => 'Restroom',
                'image_filename' => null,
                'image_url' => 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7',
                'image_filemime' => null,
                'image_filesize' => null,
                'image_width' => null,
                'image_height' => null,
            ]]]);
    }

    public function test_annotation_appears_once_per_type(): void
    {
        $area = AnnotationType::factory()
            ->for(AnnotationCategory::factory(['title' => 'Area']), 'category')
            ->create(['title' => 'Gallery']);
        $amenity = AnnotationType::factory()
            ->for(AnnotationCategory::factory(['title' => 'Amenity']), 'category')
            ->create(['title' => 'Elevator']);
        $annotation = Annotation::factory()->create();
        $annotation->types()->attach([$area->id, $amenity->id]);

        $annotations = $this->getJson('/api/map_annotations')->assertOk()->json('map_annontations');

        $this->assertEqualsCanonicalizing(
            [$this->cantorPair($annotation->id, $area->id), $this->cantorPair($annotation->id, $amenity->id)],
            array_keys($annotations),
        );
        $byType = collect($annotations)->keyBy('annotation_type');
        $this->assertSame('Gallery', $byType['Text']['text_type']);
        $this->assertNull($byType['Text']['amenity_type']);
        $this->assertSame('Elevator', $byType['Amenity']['amenity_type']);
        $this->assertNull($byType['Amenity']['text_type']);
    }

    private function cantorPair(int $x, int $y): int
    {
        return (($x + $y) * ($x + $y + 1)) / 2 + $y;
    }
}
