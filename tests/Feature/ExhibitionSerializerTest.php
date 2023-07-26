<?php

namespace Tests\Feature;

use App\Models\Exhibition;
use App\Repositories\Serializers\ExhibitionSerializer;
use Tests\TestCase;

class ExhibitionSerializerTest extends TestCase
{
    public function test_serialize(): void
    {
        $serializer = new ExhibitionSerializer();
        $exhibitions = Exhibition::factory()->count(3)->sorted()->make();
        $serialized = $serializer->serialize($exhibitions);

        $this->assertArrayHasKey('exhibitions', $serialized);
        $this->assertCount(3, $serialized['exhibitions']);
        foreach ($serialized['exhibitions'] as $index => $exhibition) {
            $this->assertArrayHasKey('title', $exhibition);
            $this->assertArrayHasKey('image_url', $exhibition);
            $this->assertArrayHasKey('exhibition_id', $exhibition);
            $this->assertEquals($index, $exhibition['sort']);
        }
    }
}
