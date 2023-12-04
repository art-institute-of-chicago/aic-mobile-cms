<?php

namespace Tests\Feature;

use App\Models\Api\Audio as ApiAudio;
use App\Models\Audio;
use App\Repositories\Serializers\AudioSerializer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\MockApi;
use Tests\TestCase;

class AudioSerializerTest extends TestCase
{
    use MockApi;
    use RefreshDatabase;

    public function test_serialize(): void
    {
        $defaultAudios = Audio::factory()->count(3)->make();
        $allAudios = $defaultAudios->map(function ($defaultAudio) {
            $translatedAudios = Audio::factory()->translated()->count(3)->make();
            return $translatedAudios->push($defaultAudio);
        });
        $mockAudioResponses = ApiAudio::factory()
            ->count(12)
            ->make()
            ->map(fn ($audio) => $this->mockApiModelReponse($audio))
            ->toArray();
        $this->addMockApiResponses($mockAudioResponses);
        $serializer = new AudioSerializer();
        $serialized = $serializer->serialize($allAudios);

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
