<?php

namespace Tests\Unit;

use App\Repositories\Serializers\AssociativeArraySerializer;
use PHPUnit\Framework\TestCase;

class AssociativeArraySerializerTest extends TestCase
{
    public function test_collection_returns_an_associative_array(): void
    {
        $data = [
            [
                123 => [
                    'firstKey' => 'first',
                    'secondKey' => 'second',
                    'thirdKey' => 'third',
                ],
            ],
            [
                456 => [
                    'fourthKey' => 'first',
                    'fifthKey' => 'second',
                    'sixthKey' => 'third',
                ],
            ],
        ];
        $serializer = new AssociativeArraySerializer();
        $collection = $serializer->collection('test', $data);
        $this->assertArrayHasKey('test', $collection, 'The collection is serialized with the correct key');
        foreach ($data as $datum) {
            foreach ($datum as $id => $record) {
                $this->assertContains(
                    $id,
                    $collection['test']->keys(),
                    'The serialized collection contains the record id',
                );
                $this->assertContainsEquals(
                    $record,
                    $collection['test'],
                    'The serialized collection contains the record',
                );
            }
        }
    }
}
