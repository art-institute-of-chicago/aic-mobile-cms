<?php

namespace Tests\Feature;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;
use Tests\MockApi;
use Tests\TestCase;

/**
 * Smoke tests that guard the app's HTTP surface, console and schedule through
 * framework upgrades. Any request to the data API that is not explicitly
 * mocked fails, so these tests never reach the network.
 */
class SmokeTest extends TestCase
{
    use MockApi;
    use RefreshDatabase;

    public static function emptyFeeds(): array
    {
        return [
            'audio_files' => ['/api/audio_files', ['audio_files' => []]],
            'dashboard' => ['/api/dashboard', ['dashboard' => ['featured_tours' => [], 'featured_exhibitions' => []]]],
            'exhibitions' => ['/api/exhibitions', ['exhibitions' => []]],
            'galleries' => ['/api/galleries', ['galleries' => []]],
            'general_info' => ['/api/general_info', ['general_info' => ['translations' => [
                ['language' => 'es'],
                ['language' => 'fr'],
                ['language' => 'ko'],
                ['language' => 'zh-hant'],
            ]]]],
            'map_annotations' => ['/api/map_annotations', ['map_annontations' => []]],
            'map_floors' => ['/api/map_floors', ['map_floors' => []]],
            'messages' => ['/api/messages', ['messages' => []]],
            'objects' => ['/api/objects', ['objects' => []]],
            'tour_categories' => ['/api/tour_categories', ['tour_categories' => []]],
            'tours' => ['/api/tours', ['tours' => []]],
        ];
    }

    #[DataProvider('emptyFeeds')]
    public function test_feed_returns_exact_json_without_content(string $uri, array $expected): void
    {
        $this->getJson($uri)
            ->assertOk()
            ->assertHeader('Content-Type', 'application/json')
            ->assertExactJson($expected);
        $this->assertApiRequestCount(0);
    }

    public function test_data_feed_returns_uris(): void
    {
        $this->getJson('/api/data')
            ->assertOk()
            ->assertExactJson(['data' => [
                'artworks_endpoint' => '/api/v1/artworks',
                'autocomplete_endpoint' => '/api/v1/autocomplete',
                'data_api_url' => 'https://api.artic.edu',
                'events_endpoint' => '/api/v1/legacy-events',
                'events_endpoint_v2' => '/api/v1/event-occurrences',
                'exhibitions_endpoint' => '/api/v1/exhibitions',
                'galleries_endpoint' => '/api/v1/galleries',
                'image_server_url' => 'https://www.artic.edu/iiif/2',
                'images_endpoint' => '/api/v1/images',
                'membership_url' => 'https://sales.artic.edu/memberships?&utm_medium=mobile-app&utm_source=iphone&utm_campaign=join',
                'multisearch_endpoint' => '/api/v1/msearch',
                'tickets_url' => 'https://sales.artic.edu/admissiondate?&utm_medium=mobile-app&utm_source=iphone&utm_campaign=buy-tickets',
                'tours_endpoint' => '/api/v1/tours',
                'website_url' => 'https://www.artic.edu?&utm_medium=mobile-app&utm_source=iphone&utm_campaign=homepage',
            ]]);
    }

    public function test_search_feed_queries_the_api_for_suggested_objects(): void
    {
        $this->addMockApiResponses($this->mockApiSearchResponse());

        $this->getJson('/api/search')
            ->assertOk()
            ->assertExactJson(['search' => [
                'search_strings' => ['Essentials', 'Impressionism', 'A Sunday on La Grande Jatte - 1884'],
                'search_objects' => [],
            ]]);
        $this->assertApiRequestCount(1);
    }

    public function test_app_data_combines_every_section_in_legacy_order(): void
    {
        $this->addMockApiResponses($this->mockApiSearchResponse());

        $response = $this->getJson('/api/appData-v3')->assertOk();

        $this->assertSame(
            [
                'dashboard',
                'general_info',
                'galleries',
                'objects',
                'audio_files',
                'tours',
                'map_annontations',
                'map_floors',
                'messages',
                'tour_categories',
                'exhibitions',
                'data',
                'search',
            ],
            array_keys($response->json()),
        );
    }

    public function test_unknown_api_route_returns_json_404(): void
    {
        config(['app.debug' => false]);

        $this->getJson('/api/does-not-exist')
            ->assertNotFound()
            ->assertExactJson(['message' => 'The route api/does-not-exist could not be found.']);
    }

    public function test_unknown_route_returns_html_404(): void
    {
        $this->get('/does-not-exist')
            ->assertNotFound()
            ->assertHeader('Content-Type', 'text/html; charset=UTF-8');
    }

    public function test_server_error_returns_json_500_without_details(): void
    {
        config(['app.debug' => false]);
        Route::middleware('api')->get('/api/smoke-test-error', function () {
            throw new RuntimeException('Secret details');
        });

        $this->getJson('/api/smoke-test-error')
            ->assertStatus(500)
            ->assertExactJson(['message' => 'Server Error']);
    }

    public function test_api_sends_cors_headers(): void
    {
        $this->getJson('/api/data', ['Origin' => 'https://example.com'])
            ->assertOk()
            ->assertHeader('Access-Control-Allow-Origin', '*');
    }

    public function test_home_redirects_to_cms_dashboard(): void
    {
        $this->get('/')->assertRedirect(route('twill.dashboard'));
    }

    public function test_cms_redirects_guests_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_cms_rejects_guests_requesting_json(): void
    {
        $this->getJson('/admin')
            ->assertUnauthorized()
            ->assertExactJson(['message' => 'Unauthenticated.']);
    }

    public function test_commands_are_registered(): void
    {
        $this->assertArrayHasKey('app:migrate-data', Artisan::all());
    }

    public function test_no_tasks_are_scheduled(): void
    {
        $this->assertSame([], app(Schedule::class)->events());
    }
}
