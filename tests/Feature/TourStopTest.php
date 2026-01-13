<?php

namespace Tests\Feature;

use App\Models\LoanObject;
use App\Models\Selector;
use App\Models\Stop;
use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TourStopTest extends TestCase
{
    use RefreshDatabase;

    public function test_unpublishing_tour_unpublishes_stops(): void
    {
        $stop = Stop::factory(['published' => true])->has(Selector::factory())->create();
        $tour = Tour::factory(['published' => true])->create();
        $tour->stops()->attach($stop);
        $this->assertTrue($stop->published);
        $this->assertEquals(1, Stop::published()->count());

        $tour->published = false;
        $tour->save();
        $stop->refresh();
        $this->assertFalse($stop->published);
        $this->assertEquals(0, Stop::published()->count());
    }
}
