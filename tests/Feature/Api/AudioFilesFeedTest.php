<?php

namespace Tests\Feature\Api;

use App\Models\Api\Audio as ApiAudio;
use App\Models\ApiRelation;
use App\Models\Audio;
use App\Models\Selector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTourContent;
use Tests\MockApi;
use Tests\TestCase;

class AudioFilesFeedTest extends TestCase
{
    use CreatesTourContent;
    use MockApi;
    use RefreshDatabase;

    public function test_audio_output_with_translations(): void
    {
        $tour = $this->createTour();
        $stop = $this->createStop($tour, $this->createLoanObject(), position: 1);
        $english = $this->createAudio($stop->selector, [
            'id' => 101,
            'datahub_id' => 'en-audio',
            'locale' => 'en',
            'title' => 'Introduction',
            'content' => 'https://example.com/en.mp3',
            'transcript' => '<p>Hello.</p><p>Welcome.</p>',
        ]);
        $this->createAudio($stop->selector, [
            'id' => 102,
            'datahub_id' => 'es-audio',
            'locale' => 'es',
            'title' => 'Introducción',
            'content' => 'https://example.com/es.mp3',
            'transcript' => '<p>Hola.</p>',
        ]);
        $this->mockApiAudios(['en-audio', 'es-audio']);

        $this->getJson('/api/audio_files')
            ->assertOk()
            ->assertExactJson(['audio_files' => [$english->id => [
                'title' => 'Introduction',
                'nid' => '101',
                'audio_file_url' => 'https://example.com/en.mp3',
                'audio_transcript' => "Hello.\r\nWelcome.\r\n",
                'track_title' => null,
                // Keyed by the translation's index among the selector's audios
                'translations' => [1 => [
                    'language' => 'es',
                    'title' => 'Introducción',
                    'track_title' => null,
                    'audio_fiilename' => null,
                    'audio_file_url' => 'https://example.com/es.mp3',
                    'audio_filemime' => null,
                    'audio_filesize' => null,
                    'audio_transcript' => "Hola.\r\n",
                    'credits' => null,
                ]],
            ]]]);
    }

    public function test_lists_audio_for_published_stops_and_tours(): void
    {
        $tour = $this->createTour();
        $unpublishedTour = $this->createTour(['published' => false]);
        $stop = $this->createStop($tour, $this->createLoanObject(), position: 1);
        $unpublishedStop = $this->createStop($tour, $this->createLoanObject(), position: 2, stopAttributes: ['published' => false]);
        $stopOnUnpublishedTour = $this->createStop($unpublishedTour, $this->createLoanObject(), position: 1);
        $tourSelector = Selector::factory()->make();
        $tourSelector->selectable()->associate($tour)->save();
        $this->createAudio($stop->selector, ['id' => 201, 'datahub_id' => 'stop']);
        $this->createAudio($tourSelector, ['id' => 202, 'datahub_id' => 'tour']);
        $this->createAudio($unpublishedStop->selector, ['id' => 203, 'datahub_id' => 'unpublished-stop']);
        $this->createAudio($stopOnUnpublishedTour->selector, ['id' => 204, 'datahub_id' => 'unpublished-tour']);
        $this->mockApiAudios(['stop', 'tour']);

        $ids = array_keys($this->getJson('/api/audio_files')->assertOk()->json('audio_files'));

        $this->assertEqualsCanonicalizing([201, 202], $ids);
    }

    private function createAudio(Selector $selector, array $attributes): Audio
    {
        $audio = Audio::factory()->create(array_merge(['locale' => 'en'], $attributes));
        $relation = ApiRelation::create(['datahub_id' => $audio->datahub_id]);
        $selector->apiElements()->attach($relation, [
            'position' => $selector->apiElements()->count(),
            'relation' => 'apiAudios',
        ]);

        return $audio;
    }

    private function mockApiAudios(array $datahubIds): void
    {
        $this->addMockApiResponses(array_map(
            fn ($id) => $this->mockApiModelReponse(ApiAudio::factory()->make(['id' => $id])),
            $datahubIds,
        ));
    }
}
