<?php

namespace Tests\Feature\Api;

use App\Models\Label;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\MockApi;
use Tests\TestCase;

class GeneralInfoFeedTest extends TestCase
{
    use MockApi;
    use RefreshDatabase;

    public function test_labels_with_translations(): void
    {
        $welcome = Label::factory()->create(['key' => 'welcome', 'text' => 'Welcome']);
        $welcome->translations()->create(['locale' => 'es', 'active' => true, 'text' => 'Bienvenidos']);
        Label::factory()->create(['key' => 'map_title', 'text' => 'Map']);

        $this->getJson('/api/general_info')
            ->assertOk()
            ->assertExactJson(['general_info' => [
                'translations' => [
                    ['language' => 'es', 'welcome' => 'Bienvenidos', 'map_title' => null],
                    ['language' => 'fr', 'welcome' => null, 'map_title' => null],
                    ['language' => 'ko', 'welcome' => null, 'map_title' => null],
                    ['language' => 'zh-hant', 'welcome' => null, 'map_title' => null],
                ],
                'welcome' => 'Welcome',
                'map_title' => 'Map',
            ]]);
    }
}
