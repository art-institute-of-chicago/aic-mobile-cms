<?php

namespace App\Models\Transformers;

use A17\Twill\Models\Contracts\TwillModelContract;
use League\Fractal\TransformerAbstract;
use App\Helpers\Util;

class AnnotationTransformer extends TransformerAbstract
{
    public function transform(TwillModelContract $annotation)
    {
        $type = $annotation->types->first();
        $category = $type->category;
        $nid = Util::cantorPair($annotation->id, $annotation->annotation_type_id);
        return [
            $nid => [
                'title' => $annotation->title,
                'status' => "1", // Legacy from Drupal
                'nid' => $nid, // Legacy from Drupal
                'type' => 'map_annotation', // Legacy from Drupal
                'translations' => [], // TODO Determine what data the mobile app requires
                'location' => "$annotation->latitude,$annotation->longitude",
                'latitude' => $annotation->latitude,
                'longitude' => $annotation->longitude,
                'floor' => $annotation->floor?->level,
                'description' => $annotation->description,
                'label' => $annotation->label,
                'annotation_type' => $category->title == 'Area' ? 'Text' : $category->title,
                'text_type' => $category->title == 'Area' ? $type->title : null,
                'amenity_type' => $category->title == 'Amenity' ? $type->title : null,
                'image_filename' => null, // Legacy from Drupal
                'image_url' => $annotation->image('upload'),
                'image_filemime' => null, // Legacy from Drupal
                'image_filesize' => null, // Legacy from Drupal
                'image_width' => null, // Legacy from Drupal
                'image_height' => null, // Legacy from Drupal
            ]
        ];
    }
}
