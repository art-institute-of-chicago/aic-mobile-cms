<?php

namespace Tests\Feature\Api;

use App\Models\Selector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTourContent;
use Tests\MockApi;
use Tests\TestCase;

class ToursFeedTest extends TestCase
{
    use CreatesTourContent;
    use MockApi;
    use RefreshDatabase;

    /**
     * Twill's fallback when a model has no image
     */
    private const BLANK_IMAGE = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

    public function test_lists_published_visible_tours_by_position(): void
    {
        $this->createTour(['title' => 'Second', 'position' => 2]);
        $this->createTour(['title' => 'First', 'position' => 1]);
        $this->createTour(['title' => 'Unpublished', 'position' => 3, 'published' => false]);
        $this->createTour(['title' => 'Ended', 'position' => 4, 'publish_end_date' => now()->subDay()]);
        $this->createTour(['title' => 'Scheduled', 'position' => 5, 'publish_start_date' => now()->addDay()]);
        $this->createTour(['title' => 'Running', 'position' => 6, 'publish_start_date' => now()->subDay(), 'publish_end_date' => now()->addDay()]);

        $titles = collect($this->getJson('/api/tours')->assertOk()->json('tours'))->pluck('title');

        $this->assertSame(['First', 'Second', 'Running'], $titles->all());
        $this->assertApiRequestCount(0);
    }

    public function test_tour_output(): void
    {
        $tour = $this->createTour([
            'title' => 'Essentials Tour',
            'description' => '<p>Highlights of the collection.</p>',
            'intro' => '<p>Welcome.</p>',
            'duration' => 25,
            'position' => 7,
        ]);
        $tour->translations()->create([
            'locale' => 'es',
            'active' => true,
            'title' => 'Recorrido esencial',
            'description' => '<p>Lo más destacado.</p>',
            'intro' => '<p>Bienvenidos.</p>',
        ]);
        $tour->translations()->create([
            'locale' => 'fr',
            'active' => false,
            'title' => 'Visite inactive',
        ]);
        $selector = Selector::factory()->make(['number' => 345]);
        $selector->selectable()->associate($tour)->save();

        $this->getJson('/api/tours')
            ->assertOk()
            ->assertExactJson(['tours' => [[
                'title' => 'Essentials Tour',
                'nid' => (string) $tour->id,
                'location' => null,
                'latitude' => null,
                'longitude' => null,
                'floor' => null,
                'image_url' => self::BLANK_IMAGE,
                'thumbnail_full_path' => self::BLANK_IMAGE,
                'large_image_full_path' => self::BLANK_IMAGE,
                'selector_number' => '345',
                'description' => '<p>Highlights of the collection.</p>',
                'intro' => '<p>Welcome.</p>',
                'tour_duration' => '25 minutes',
                'tour_audio' => null,
                'category' => null,
                'weight' => 7,
                'tour_stops' => [],
                'translations' => [[
                    'language' => 'es',
                    'title' => 'Recorrido esencial',
                    'description' => '<p>Lo más destacado.</p>',
                    'description_html' => '<p>Lo más destacado.</p>',
                    'intro' => '<p>Bienvenidos.</p>',
                    'intro_html' => '<p>Bienvenidos.</p>',
                    'tour_duration' => '25 minutos',
                ]],
            ]]]);
    }

    public function test_tour_stops_include_only_published_stops_for_on_view_objects(): void
    {
        $tour = $this->createTour();
        $onView = $this->createLoanObject();
        $offView = $this->createLoanObject(['is_on_view' => false]);
        $this->createStop($tour, $onView, position: 2);
        $this->createStop($tour, $this->createLoanObject(), position: 1, stopAttributes: ['published' => false]);
        $this->createStop($tour, $this->createLoanObject(), position: 3, selectorAttributes: ['published' => false]);
        $this->createStop($tour, $offView, position: 4);

        $this->getJson('/api/tours')
            ->assertOk()
            ->assertJsonPath('tours.0.tour_stops', [[
                'object' => $this->loanObjectNid($onView->id),
                'audio_id' => '',
                'audio_bumper' => null,
                'sort' => 2,
            ]]);
    }

    public function test_tours_are_cached(): void
    {
        $this->createTour(['title' => 'Cached']);
        $this->getJson('/api/tours')->assertOk();

        $this->createTour(['title' => 'Added later']);

        $titles = collect($this->getJson('/api/tours')->json('tours'))->pluck('title');
        $this->assertSame(['Cached'], $titles->all());
    }

    private function loanObjectNid(int $id): int
    {
        // Cantor pairing of the loan object type (2) and its id
        return ((2 + $id) * (2 + $id + 1)) / 2 + $id;
    }
}
