<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesTourContent;
use Tests\MockApi;
use Tests\TestCase;

class AppDataFeedTest extends TestCase
{
    use CreatesTourContent;
    use MockApi;
    use RefreshDatabase;

    public function test_sections_match_the_individual_feeds(): void
    {
        $tour = $this->createTour(['featured' => true]);
        $this->createStop($tour, $this->createLoanObject(), position: 1);
        $this->addMockApiResponses([$this->mockApiSearchResponse(), $this->mockApiSearchResponse()]);

        $appData = $this->getJson('/api/appData-v3')->assertOk()->json();

        foreach (['dashboard', 'objects', 'tours', 'data', 'search', 'general_info'] as $section) {
            $this->assertSame(
                $this->getJson("/api/$section")->json(),
                [$section => $appData[$section]],
                "The $section section differs from its feed",
            );
        }
    }

    public function test_app_data_is_cached(): void
    {
        $this->createTour(['title' => 'Cached']);
        $this->addMockApiResponses($this->mockApiSearchResponse());
        $first = $this->getJson('/api/appData-v3')->assertOk()->json();

        $this->createTour(['title' => 'Added later', 'featured' => true]);

        $this->assertSame($first, $this->getJson('/api/appData-v3')->assertOk()->json());
        $this->assertApiRequestCount(1);
    }
}
