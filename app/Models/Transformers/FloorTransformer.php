<?php

namespace App\Models\Transformers;

use App\Models\Floor;
use A17\Twill\Models\Contracts\TwillModelContract;
use League\Fractal\TransformerAbstract;

class FloorTransformer extends TransformerAbstract
{
    public function transform(TwillModelContract $floor)
    {
        $floorPlan = $floor->fileObject('floor_plan');
        // For backward compatibility, the key for level LL must be 'map_floor0'.
        $mapFloor = $floor->level == 'LL' ? '0' : $floor->level;
        return [
            "map_floor$mapFloor" => [
                'label' => $floor->level,
                'floor_plan' => $floorPlan ? secure_url('storage/uploads/' . $floorPlan?->uuid) : null,
                'anchor_pixel_1' => Floor::ANCHOR_PIXELS[0],
                'anchor_pixel_2' => Floor::ANCHOR_PIXELS[1],
                'anchor_location_1' => FLOOR::ANCHOR_LOCATIONS[0],
                'anchor_location_2' => FLOOR::ANCHOR_LOCATIONS[1],
                'tile_images' => null, // Legacy from Drupal
            ]
        ];
    }
}
