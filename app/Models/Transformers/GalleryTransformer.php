<?php

namespace App\Models\Transformers;

use A17\Twill\Models\Contracts\TwillModelContract;
use League\Fractal\TransformerAbstract;

class GalleryTransformer extends TransformerAbstract
{
    public function transform(TwillModelContract $gallery)
    {
        return [
            $gallery->id => [
                'title' => $gallery->title,
                'nid' => (string) $gallery->id, // Legacy from Drupal
                'location' => $gallery->getApiModelFilledCached()->latlon ?? $gallery->latlon,
                'latitude' => $gallery->getApiModelFilledCached()->latitude ?? $gallery->latitude,
                'longitude' => $gallery->getApiModelFilledCached()->longitude ?? $gallery->longitude,
                'gallery_id' => (string) $gallery->id,
                'tgn_id' => null,// Legacy from Drupal
                'closed' => $gallery->getApiModelFilledCached()->is_closed ?? $gallery->is_closed,
                'number' => $gallery->getApiModelFilledCached()->number ?? $gallery->number,
                'floor' => $gallery->getApiModelFilledCached()->floor ?? $gallery->floor,
                'source_updated_at' => $gallery->source_updated_at,
                'updated_at' => $gallery->updated_at,
            ]
        ];
    }
}
