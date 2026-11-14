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
