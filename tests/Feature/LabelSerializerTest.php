<?php

namespace Tests\Feature;

use App\Models\Label;
use App\Repositories\Serializers\LabelSerializer;
use Tests\TestCase;

class LabelSerializerTest extends TestCase
{
    public function test_serialize(): void
    {
        $labels = Label::factory()->count(10)->make();
        $serializer = new LabelSerializer();
        $serialized = $serializer->serialize($labels);

        foreach ($labels as $label) {
            $this->assertArrayHasKey($label->key, $serialized['labels']);
            $this->assertEquals($label->text, $serialized['labels'][$label->key]);
        }
    }
}
