<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OutputRouteTest extends TestCase
{
    public function test_appData_v3_route(): void
    {
        $response = $this->get('/api/appData-v3');
        $response->assertStatus(200);
        $json = $response->json();
        $this->assertArrayHasKey('dashboard', $json);
        $this->assertArrayHasKey('general_info', $json);
        $this->assertArrayHasKey('galleries', $json);
        $this->assertArrayHasKey('objects', $json);
        $this->assertArrayHasKey('audio_files', $json);
        $this->assertArrayHasKey('tours', $json);
        $this->assertArrayHasKey('map_annontations', $json, 'The "annotations" is misspelled');
        $this->assertArrayHasKey('map_floors', $json);
        $this->assertArrayHasKey('messages', $json);
        $this->assertArrayHasKey('tour_categories', $json);
        $this->assertArrayHasKey('exhibitions', $json);
        $this->assertArrayHasKey('data', $json);
        $this->assertArrayHasKey('search', $json);
    }
}
