<?php

namespace Tests\Feature;

use App\Models\Api\CollectionObject as ApiCollectionObject;
use App\Models\Api\Gallery as ApiGallery;
use App\Models\CollectionObject;
use App\Models\LoanObject;
use App\Repositories\Serializers\ObjectSerializer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\MockApi;
use Tests\TestCase;

class ObjectSerializerTest extends TestCase
{
    use MockApi;
    use RefreshDatabase;

    public function test_serialize(): void
    {
        $collectionObjectCount = 3;
        $loanObjectCount = 3;
        $mockCollectionObjectResponses = ApiCollectionObject::factory()
                ->count($collectionObjectCount)
                ->make()
                ->map(fn ($collectionObject) => $this->mockApiModelReponse($collectionObject));
        $mockGalleryResponses = ApiGallery::factory()
            ->count($collectionObjectCount + $loanObjectCount)
            ->make()
            ->map(fn ($gallery) => $this->mockApiModelReponse($gallery));
        $responses = collect()->concat($mockCollectionObjectResponses)->concat($mockGalleryResponses)->toArray();
        $this->addMockApiResponses($responses);
        $collectionObjects = CollectionObject::factory()->count($collectionObjectCount)->create();
        $loanObjects = LoanObject::factory()->count($loanObjectCount)->create();
        $objects = collect()->concat($collectionObjects)->concat($loanObjects);
        $serializer = new ObjectSerializer();
        $serialized = $serializer->serialize($objects);

        $this->assertArrayHasKey('objects', $serialized);
        $this->assertCount($collectionObjectCount + $loanObjectCount, $serialized['objects']);
        foreach ($serialized['objects'] as $id => $object) {
            $this->assertEquals($id, $object['nid']);
            $this->assertArrayHasKey('title', $object);
            $this->assertArrayHasKey('id', $object);
            $this->assertArrayHasKey('artist_culture_place_delim', $object);
            $this->assertArrayHasKey('credit_line', $object);
            $this->assertArrayHasKey('catalogue_display', $object);
            $this->assertArrayHasKey('edition', $object);
            $this->assertArrayHasKey('fiscal_year_deaccession', $object);
            $this->assertArrayHasKey('copyright_notice', $object);
            $this->assertArrayHasKey('on_loan_display', $object);
            $this->assertArrayHasKey('location', $object);
            $this->assertArrayHasKey('image_url', $object);
            $this->assertArrayHasKey('thumbnail_full_path', $object);
            $this->assertArrayHasKey('large_image_crop_v2', $object);
            $this->assertArrayHasKey('large_image_full_path', $object);
            $this->assertArrayHasKey('gallery_location', $object);
            $this->assertArrayHasKey('audio_commentary', $object);
        }
    }
}
