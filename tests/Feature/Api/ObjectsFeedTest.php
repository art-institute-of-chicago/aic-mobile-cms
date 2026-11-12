<?php

namespace Tests\Feature\Api;

use App\Models\Selector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTourContent;
use Tests\MockApi;
use Tests\TestCase;

class ObjectsFeedTest extends TestCase
{
    use CreatesTourContent;
    use MockApi;
    use RefreshDatabase;

    public function test_loan_object_output(): void
    {
        $tour = $this->createTour();
        $object = $this->createLoanObject([
            'title' => 'Borrowed Painting',
            'artist_display' => 'Jane Doe, American, 1900–1980',
            'credit_line' => 'Lent by a private collection',
            'copyright_notice' => '© Jane Doe',
            'latitude' => 41.8796,
            'longitude' => -87.6237,
        ]);
        $this->createStop($tour, $object, position: 1, selectorAttributes: ['number' => 123]);
        $nid = $this->loanObjectNid($object->id);

        $this->getJson('/api/objects')
            ->assertOk()
            ->assertExactJson(['objects' => [$nid => [
                'title' => 'Borrowed Painting',
                'nid' => $nid,
                'id' => null,
                'artist_culture_place_delim' => 'Jane Doe, American, 1900–1980',
                'credit_line' => 'Lent by a private collection',
                'catalogue_display' => null,
                'edition' => null,
                'fiscal_year_deaccession' => null,
                'copyright_notice' => '© Jane Doe',
                'on_loan_display' => null,
                'location' => '41.8796000000000,-87.6237000000000',
                'image_url' => null,
                'thumbnail_crop_v2' => null,
                'thumbnail_full_path' => self::BLANK_IMAGE,
                'large_image_crop_v2' => null,
                'large_image_full_path' => self::BLANK_IMAGE,
                'gallery_location' => null,
                'audio_commentary' => [[
                    'object_selector_number' => '123',
                    'audio' => null,
                ]],
            ]]]);
        $this->assertApiRequestCount(0);
    }

    public function test_lists_only_objects_with_published_stops_on_published_tours(): void
    {
        $tour = $this->createTour();
        $unpublishedTour = $this->createTour(['published' => false]);
        $included = $this->createLoanObject(['title' => 'Included']);
        $this->createStop($tour, $included, position: 1);
        $this->createStop($tour, $this->createLoanObject(['title' => 'Unpublished stop']), position: 2, stopAttributes: ['published' => false]);
        $this->createStop($tour, $this->createLoanObject(['title' => 'Unpublished selector']), position: 3, selectorAttributes: ['published' => false]);
        $this->createStop($unpublishedTour, $this->createLoanObject(['title' => 'Unpublished tour']), position: 1);
        $tourSelector = Selector::factory()->make();
        $tourSelector->selectable()->associate($tour);
        $tourSelector->object()->associate($this->createLoanObject(['title' => 'Tour selector']));
        $tourSelector->save();

        $titles = collect($this->getJson('/api/objects')->assertOk()->json('objects'))->pluck('title');

        $this->assertSame(['Included'], $titles->values()->all());
    }

    public function test_collection_objects_are_retrieved_from_the_api(): void
    {
        $tour = $this->createTour();
        $stop = $this->createStop($tour, $this->createLoanObject(), position: 1);
        $stop->selector->update(['object_type' => 'collectionObject', 'object_id' => 27992]);
        $this->addMockApiResponses($this->mockApiSearchResponse());

        $this->getJson('/api/objects')
            ->assertOk()
            ->assertExactJson(['objects' => []]);
        $this->assertApiRequestCount(1);
        $this->assertApiRequestReceived('GET', '/api/v1/artworks');
        parse_str($this->transactions[0]['request']->getUri()->getQuery(), $query);
        $this->assertSame(['ids' => '27992'], json_decode($query['params'], true));
    }

    private const BLANK_IMAGE = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

    private function loanObjectNid(int $id): int
    {
        // Cantor pairing of the loan object type (2) and its id
        return ((2 + $id) * (2 + $id + 1)) / 2 + $id;
    }
}
