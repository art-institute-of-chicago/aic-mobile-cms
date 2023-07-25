<?php

namespace App\Models\Transformers;

use A17\Twill\Models\Contracts\TwillModelContract;
use League\Fractal\TransformerAbstract;

class ObjectTransformer extends TransformerAbstract
{
    public function transform(TwillModelContract $object)
    {
        return [
            $object->id => [
                'title' => $object->title,
                'nid' => (string) $object->id, // Legacy from Drupal
                'id' => $object->id,
                'artist_culture_place_delim' => $object->artist_display,
                'credit_line' => $object->credit_line,
                'catalogue_display' => $object->catalogue_display,
                'edition' => $object->edition,
                'fiscal_year_deaccession' => $object->fiscal_year_deaccession,
                'copyright_notice' => $object->copyright_notice,
                'on_loan_display' => $object->on_loan_display,
                'location' => $object->latlon,
                'image_url' => $object->image_url,
                'thumbnail_full_path' => null, // TODO load images from iiif
                'large_image_crop_v2' => [ // TODO crop images
                    'x' => null,
                    'y' => null,
                    'width' => null,
                    'height' => null,
                ],
                'large_image_full_path' => null,
                'gallery_location' => null, // TODO fake retrieving the gallery relation in a testing env
                'audio_commentary' => [], // TODO Tours and Audio files
            ]
        ];
    }
}
