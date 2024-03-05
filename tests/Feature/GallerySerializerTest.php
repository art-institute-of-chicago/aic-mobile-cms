<?php

namespace Tests\Feature;

use App\Models\Gallery;
use App\Models\Api\Gallery as ApiGallery;
use App\Repositories\Serializers\GallerySerializer;
use Tests\TestCase;
use Tests\MockApi;

class GallerySerializerTest extends TestCase
{
    use MockApi;

    public function test_serialize(): void
    {
        $mockGalleryResponses = ApiGallery::factory()
            ->count(3)
            ->make()
            ->map(fn ($gallery) => $this->mockApiModelReponse($gallery));
        $responses = collect($mockGalleryResponses)->toArray();
        $this->addMockApiResponses($responses);

        $serializer = new GallerySerializer();
        $galleries = Gallery::factory()->count(3)->make();
        $serialized = $serializer->serialize($galleries);

        $this->assertArrayHasKey('galleries', $serialized);
        $this->assertCount(3, $serialized['galleries']);
        foreach ($serialized['galleries'] as $id => $gallery) {
            $this->assertArrayHasKey('title', $gallery);
            $this->assertArrayHasKey('location', $gallery);
            $this->assertArrayHasKey('latitude', $gallery);
            $this->assertArrayHasKey('longitude', $gallery);
            $this->assertArrayHasKey('gallery_id', $gallery);
            $this->assertArrayHasKey('closed', $gallery);
            $this->assertArrayHasKey('number', $gallery);
            $this->assertArrayHasKey('floor', $gallery);
            $this->assertArrayHasKey('source_updated_at', $gallery);
            $this->assertArrayHasKey('updated_at', $gallery);
            $this->assertEquals($id, $gallery['gallery_id']);
        }
    }
}
