<?php

namespace Tests\Feature;

use App\Models\CollectionObject;
use App\Repositories\Serializers\ObjectSerializer;
use Tests\TestCase;

class ObjectSerializerTest extends TestCase
{
    public function test_serialize(): void
    {
        $serializer = new ObjectSerializer();
        $objects = CollectionObject::factory()->count(3)->make();
        $serialized = $serializer->serialize($objects);

        $this->assertArrayHasKey('objects', $serialized);
        $this->assertCount(3, $serialized['objects']);
        foreach ($serialized['objects'] as $id => $object) {
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
            $this->assertArrayHasKey('x', $object['large_image_crop_v2']);
            $this->assertArrayHasKey('y', $object['large_image_crop_v2']);
            $this->assertArrayHasKey('width', $object['large_image_crop_v2']);
            $this->assertArrayHasKey('height', $object['large_image_crop_v2']);
            $this->assertArrayHasKey('large_image_full_path', $object);
            $this->assertArrayHasKey('gallery_location', $object);
            $this->assertArrayHasKey('audio_commentary', $object);
            $this->assertEquals($id, $object['id']);
        }
    }
}
