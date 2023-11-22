<?php

namespace Tests\Feature;

use App\Models\CollectionObject;
use App\Repositories\Serializers\SuggestedObjectSerializer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuggestedObjectSerializerTest extends TestCase
{
    use RefreshDatabase;

    public function test_serialize(): void
    {
        $serializer = new SuggestedObjectSerializer();
        $suggestedObjects = CollectionObject::factory()->count(8)->make();
        $serialized = $serializer->serialize($suggestedObjects);

        $this->assertArrayHasKey('search_objects', $serialized);
        $this->assertCount($suggestedObjects->count(), $serialized['search_objects']);
        foreach ($serialized['search_objects'] as $id) {
            $this->assertIsString($id);
            $this->assertContains((int) $id, $suggestedObjects->pluck('id'));
        }
    }
}
