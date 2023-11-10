<?php

namespace App\Repositories\Serializers;

/**
 * Due to legacy constraints, the dashboard serializer is essentially a wrapper
 * around the featured tour serializer with an additional unused
 * `featured_exhibitions` key.
 */
class DashboardSerializer
{
    public function serialize($featuredTours)
    {
        $featuredTourSerializer = new FeaturedTourSerializer();
        return [
            'dashboard' =>
                array_merge(
                    $featuredTourSerializer->serialize($featuredTours),
                    ['featured_exhibitions' => []],  // Legacy from Drupal
                )
        ];
    }
}
