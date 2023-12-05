<?php

namespace App\Models\Transformers;

use A17\Twill\Models\Contracts\TwillModelContract;
use League\Fractal\TransformerAbstract;

class AnnotationTransformer extends TransformerAbstract
{
    public function transform(TwillModelContract $annotation)
    {
        $type = $annotation->types->first();
        $category = $type->category;
        return [
            "$annotation->id:$annotation->annotation_type_id" => [
                'title' => $annotation->title,
                'status' => "1", // Legacy from Drupal
                'nid' => (string) "$annotation->id:$annotation->annotation_type_id", // Legacy from Drupal
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
