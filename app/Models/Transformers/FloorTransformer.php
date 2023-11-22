<?php

namespace App\Models\Transformers;

use App\Models\Floor;
use A17\Twill\Models\Contracts\TwillModelContract;
use League\Fractal\TransformerAbstract;

class FloorTransformer extends TransformerAbstract
{
    public function transform(TwillModelContract $floor)
    {
        return [
            "map_floor$floor->level" => [
                'label' => $floor->level,
                'floor_plan' => $floor->file('floor_plan'),
                'anchor_pixel_1' => Floor::ANCHOR_PIXELS[0],
                'anchor_pixel_2' => Floor::ANCHOR_PIXELS[1],
                'anchor_location_1' => FLOOR::ANCHOR_LOCATIONS[0],
                'anchor_location_2' => FLOOR::ANCHOR_LOCATIONS[1],
                'tile_images' => null, // Legacy from Drupal
            ]
        ];
    }
}
