<?php

namespace App\Models\Transformers;

use A17\Twill\Models\Contracts\TwillModelContract;
use League\Fractal\TransformerAbstract;

class TourTransformer extends TransformerAbstract
{
    public function transform(TwillModelContract $tour)
    {
        $gallery = $tour->gallery;
        return [
            'title' => $tour->title,
            'nid' => (string) $tour->id, // Legacy from Drupal
            'translations' => $tour->translations, // TODO transform translations
            'location' => $gallery?->latlon,
            'latitude' => (string) $gallery?->latitude,
            'longitude' => (string) $gallery?->longitude,
            'floor' => $gallery?->floor,
            'image_url' => $tour->image_url,
            'thumbnail_full_path' => $tour->thumbnail_full_path,
            'large_image_full_path' => $tour->large_image_full_path,
            'selector_number' => (string) $tour->selector_number,
            'description' => $tour->description,
            'intro' => $tour->intro,
            'tour_duration' => $tour->duration_in_minutes,
            'tour_audio' => $tour->sound_id,
            'category' => null, // Legacy from Drupal
            'weight' => $tour->position,
            'tour_stops' => $tour->stops, // TODO transform tour stops
        ];
    }
}
