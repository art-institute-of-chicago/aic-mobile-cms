<?php

namespace Tests\Unit;

use App\Repositories\Serializers\FlatArraySerializer;
use PHPUnit\Framework\TestCase;

class FlatArraySerializerTest extends TestCase
{
    public function test_collection_returns_a_flattened_array(): void
    {
        $data = [
            [
                [
                    '123',
                ],
            ],
            [
                [
                    '456',
                ],
            ],
            [
                [
                    '789',
                ],
            ],
        ];
        $serializer = new FlatArraySerializer();
        $collection = $serializer->collection('test', $data);
        $this->assertArrayHasKey('test', $collection, 'The collection is serialized with the correct key');
        foreach ($data as $datum) {
            foreach ($datum as $record) {
                $id = current($record);
                $this->assertContains(
                    $id,
                    $collection['test'],
                    'The serialized collection is flattened array of ids',
                );
            }
        }
    }
}
