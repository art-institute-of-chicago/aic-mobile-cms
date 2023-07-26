<?php

namespace App\Repositories\Serializers;

use League\Fractal\Serializer\ArraySerializer;

class AssociativeArraySerializer extends ArraySerializer
{
    public function collection($resourceKey, array $data): array
    {
        return [$resourceKey => collect($data)->mapWithKeys(function (array $item, $index) {
            return [$key = array_key_first($item) => $item[$key]];
        })];
    }
}
