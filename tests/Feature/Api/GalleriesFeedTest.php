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
