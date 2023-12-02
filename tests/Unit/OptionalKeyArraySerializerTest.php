<?php

namespace Tests\Unit;

use App\Repositories\Serializers\OptionalKeyArraySerializer;
use PHPUnit\Framework\TestCase;

class OptionalKeyArraySerializerTest extends TestCase
{
    public function test_collection_return_an_array_with_a_key(): void
    {
        $data = [
            '123',
            '456',
            '789',
        ];
        $serializer = new OptionalKeyArraySerializer();
        $collection = $serializer->collection('test', $data);
        $this->assertArrayHasKey('test', $collection, 'The collection is serialized with the correct key');
    }

    public function test_collection_return_an_array_without_a_key(): void
    {
        $data = [
            '123',
            '456',
            '789',
        ];
        $serializer = new OptionalKeyArraySerializer();
        $collection = $serializer->collection(null, $data);
        $this->assertTrue(array_is_list($collection), 'The collection is serialized without a key');
    }
}
