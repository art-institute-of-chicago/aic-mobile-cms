<?php

namespace App\Repositories\Serializers;

use League\Fractal\Serializer\ArraySerializer;

class FlatArraySerializer extends ArraySerializer
{
    public function collection($resourceKey, array $data): array
    {
        return [$resourceKey => collect($data)->flatten()];
    }
}
