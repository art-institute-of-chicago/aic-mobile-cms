<?php

namespace Tests\Feature;

use App\Models\CollectionObject;
use App\Repositories\Serializers\SearchSerializer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchSerializerTest extends TestCase
{
    use RefreshDatabase;

    public function test_serialize(): void
    {
        $serializer = new SearchSerializer();
        $suggestedObjects = CollectionObject::factory()->count(2)->make();
        $serialized = $serializer->serialize($suggestedObjects);

        $this->assertArrayHasKey('search', $serialized);
        $this->assertArrayHasKey('search_strings', $serialized['search']);
        $this->assertNotEmpty($serialized['search']['search_strings']);
        $this->assertArrayHasKey('search_objects', $serialized['search']);
        $this->assertNotEmpty($serialized['search']['search_objects']);
    }
}
