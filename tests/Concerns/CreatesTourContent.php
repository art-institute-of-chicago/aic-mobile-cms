<?php

namespace Tests\Concerns;

use App\Models\LoanObject;
use App\Models\Selector;
use App\Models\Stop;
use App\Models\Tour;

/**
 * Builds the tour → stop → selector → object graph that the feeds query.
 */
trait CreatesTourContent
{
    /**
     * Twill assigns positions on creation, so an explicit position is set
     * afterwards.
     */
    protected function createTour(array $attributes = []): Tour
    {
        $position = $attributes['position'] ?? null;
        unset($attributes['position']);
        $tour = Tour::factory()->create(array_merge([
            'featured' => false,
            'gallery_id' => null,
        ], $attributes));
        if ($position !== null) {
            $tour->forceFill(['position' => $position])->save();
        }

        return $tour;
    }

    protected function createLoanObject(array $attributes = []): LoanObject
    {
        return LoanObject::factory()->create(array_merge([
            'gallery_id' => null,
            'is_on_view' => true,
        ], $attributes));
    }

    /**
     * Create a stop on the given tour whose selector points at the object.
     */
    protected function createStop(
        Tour $tour,
        LoanObject $object,
        int $position,
        array $stopAttributes = [],
        array $selectorAttributes = [],
    ): Stop {
        $stop = Stop::factory()->create(array_merge(['published' => true], $stopAttributes));
        $selector = Selector::factory()->make(array_merge(['published' => true], $selectorAttributes));
        $selector->selectable()->associate($stop);
        $selector->object()->associate($object);
        $selector->save();
        $tour->stops()->attach($stop, ['position' => $position]);

        return $stop;
    }
}
