<?php

namespace Tests\Feature;

use App\Models\Label;
use App\Repositories\Serializers\GeneralInfoSerializer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeneralInfoSerializerTest extends TestCase
{
    use RefreshDatabase;

    public function test_serialize(): void
    {
        $serializer = new GeneralInfoSerializer();
        $labels = Label::factory()->count(3)->create();
        $serialized = $serializer->serialize($labels);

        $this->assertArrayHasKey('general_info', $serialized);
        $this->assertCount(count($labels) + 1, $serialized['general_info'], 'Count of labels plus `translations` key');
        foreach ($labels as $label) {
            $this->assertArrayHasKey($label->key, $serialized['general_info']);
            $this->assertContains($label->text, $serialized['general_info']);
        }
        $this->assertArrayHasKey('translations', $serialized['general_info']);
        $this->assertCount(count(getLocales()) - 1, $serialized['general_info'], 'Count of locales minus "en"');
    }
}
