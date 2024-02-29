<?php

namespace App\Models\Transformers;

use A17\Twill\Models\Contracts\TwillModelContract;
use App\Helpers\Util;
use App\Models\CollectionObject;
use App\Models\LoanObject;
use App\Repositories\Serializers\OptionalKeyArraySerializer;
use League\Fractal\TransformerAbstract;

class ObjectTransformer extends TransformerAbstract
{
    use CustomIncludes;

    public $customIncludes = [
        'selectors' => OptionalKeyArraySerializer::class,
    ];

    public function transform(TwillModelContract $object): array
    {
        $latitude = $object->latitude ?? $object->gallery?->latitude;
        $longitude = $object->longitude ?? $object->gallery?->longitude;
        switch (get_class($object)) {
            case CollectionObject::class:
                $thumbnail = $object->getApiModel()->image('iiif', 'thumbnail');
                $image = $object->getApiModel()->image('iiif');
                $objectType = Util::COLLECTION_OBJECT;
                break;
            case LoanObject::class:
                $thumbnail = $object->image('upload', 'thumbnail');
                $image = $object->image('upload');
                $objectType = Util::LOAN_OBJECT;
                break;
            default:
                $thumbnail = null;
                $image = null;
                $objectType = 0;
        }
        $nid = Util::cantorPair($objectType, $object->id);

        return [
            $nid => $this->withCustomIncludes($object, [
                'title' => $object->title,
                'nid' => $nid, // Legacy from Drupal
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
                'thumbnail_full_path' => $thumbnail,
                'large_image_crop_v2' => null, // Legacy from Drupal
                'large_image_full_path' => $image,
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
