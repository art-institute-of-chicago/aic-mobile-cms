<?php

namespace Tests\Feature;

use App\Models\Sound;
use App\Repositories\Serializers\AudioSerializer;
use Tests\TestCase;

class AudioSerializerTest extends TestCase
{
    public function test_serialize(): void
    {
        $serializer = new AudioSerializer();
        $audios = Sound::factory()->count(3)->make();
        $serialized = $serializer->serialize($audios);

        $this->assertArrayHasKey('audio_files', $serialized);
        $this->assertCount(3, $serialized['audio_files']);
        foreach ($serialized['audio_files'] as $id => $audio) {
            $this->assertArrayHasKey('title', $audio);
            $this->assertArrayHasKey('translations', $audio);
            $this->assertArrayHasKey('audio_file_url', $audio);
            $this->assertArrayHasKey('audio_transcript', $audio);
        }
    }
}
