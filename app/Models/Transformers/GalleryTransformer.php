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
                'location' => $gallery->getApiModel()->latlon,
                'latitude' => $gallery->getApiModel()->latitude,
                'longitude' => $gallery->getApiModel()->longitude,
                'gallery_id' => (string) $gallery->id,
                'tgn_id' => null,// Legacy from Drupal
                'closed' => $gallery->getApiModel()->is_closed,
                'number' => $gallery->getApiModel()->number,
                'floor' => $gallery->getApiModel()->floor,
                'source_updated_at' => $gallery->source_updated_at,
                'updated_at' => $gallery->updated_at,
            ]
        ];
    }
}
