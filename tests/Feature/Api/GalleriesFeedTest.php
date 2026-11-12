<?php

namespace Tests\Feature\Api;

use App\Models\Api\Gallery as ApiGallery;
use App\Models\Gallery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\MockApi;
use Tests\TestCase;

class GalleriesFeedTest extends TestCase
{
    use MockApi;
    use RefreshDatabase;

    public function test_gallery_output_prefers_local_data_over_the_api(): void
    {
        $this->travelTo('2024-05-01 12:00:00');
        $gallery = Gallery::factory()->create([
            'id' => 240,
            'datahub_id' => 2147,
            'title' => 'Gallery 240',
            'floor' => '2',
            'number' => '240',
            'is_closed' => false,
            'latitude' => 41.1,
            'longitude' => -87.1,
        ]);
        $this->addMockApiResponses($this->mockApiModelReponse(ApiGallery::factory()->make([
            'id' => 2147,
            'title' => 'Gallery 240 (API)',
            'floor' => '2',
            'number' => '240',
            'is_closed' => true,
            'latitude' => 41.8796,
            'longitude' => -87.6237,
            'latlon' => '41.8796,-87.6237',
        ])));

        // The API model defers to the local model's attributes and only fills
        // in what is missing locally, such as `latlon`.
        $this->getJson('/api/galleries')
            ->assertOk()
            ->assertExactJson(['galleries' => [$gallery->id => [
                'title' => 'Gallery 240',
                'nid' => '240',
                'location' => '41.8796,-87.6237',
                'latitude' => '41.1000000000000',
                'longitude' => '-87.1000000000000',
                'gallery_id' => '240',
                'tgn_id' => null,
                'closed' => 0,
                'number' => '240',
                'floor' => '2',
                'source_updated_at' => null,
                'updated_at' => '2024-05-01T12:00:00.000000Z',
            ]]]);
        $this->assertApiRequestCount(1);
        $this->assertApiRequestReceived('GET', '/api/v1/galleries/2147');
    }

    public function test_gallery_title_falls_back_to_the_api(): void
    {
        Gallery::factory()->create(['id' => 241, 'datahub_id' => 2148, 'title' => null]);
        $this->addMockApiResponses($this->mockApiModelReponse(ApiGallery::factory()->make([
            'id' => 2148,
            'title' => 'Gallery 241',
        ])));

        $this->getJson('/api/galleries')
            ->assertOk()
            ->assertJsonPath('galleries.241.title', 'Gallery 241');
    }
}
