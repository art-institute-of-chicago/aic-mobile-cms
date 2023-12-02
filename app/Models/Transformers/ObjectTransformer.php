<?php

namespace App\Models\Transformers;

use A17\Twill\Models\Contracts\TwillModelContract;
use App\Repositories\Serializers\OptionalKeyArraysSerializer;
use League\Fractal\TransformerAbstract;

class ObjectTransformer extends TransformerAbstract
{
    use CustomIncludes;

    public $customIncludes = [
        'selectors' => OptionalKeyArraysSerializer::class,
    ];

    public function transform(TwillModelContract $object): array
    {
        $objectType = str(class_basename($object))->lcfirst();
        $latitude = $object->latitude ?? $object->gallery?->latitude;
        $longitude = $object->longitude ?? $object->gallery?->longitude;
        return [
            "$objectType:$object->id" => $this->withCustomIncludes($object, [
                'title' => $object->title,
                'nid' => "$objectType:$object->id", // Legacy from Drupal
                'id' => $object->datahub_id ? (int) $object->datahub_id : null,
                'artist_culture_place_delim' => $object->artist_display,
                'credit_line' => $object->credit_line,
                'catalogue_display' => $object->catalogue_display,
                'edition' => $object->edition,
                'fiscal_year_deaccession' => $object->fiscal_year_deaccession,
                'copyright_notice' => $object->copyright_notice,
                'on_loan_display' => $object->on_loan_display,
                'location' => $latitude . ',' . $longitude,
                'image_url' => $object->image_id, // Legacy from Drupal
                'thumbnail_crop_v2' => null, // Legacy from Drupal
                'thumbnail_full_path' => $object->image('iiif', 'thumbnail'),
                'large_image_crop_v2' => null, // Legacy from Drupal
                'large_image_full_path' => $object->image('iiif'),
                'gallery_location' => $object->gallery?->title,
            ])
        ];
    }

    public function includeSelectors($object)
    {
        $selectors = $object->selectors;
        return $this->collection($selectors, new SelectorTransformer(), 'audio_commentary');
    }
}
