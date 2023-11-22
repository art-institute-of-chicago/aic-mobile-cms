<?php

namespace App\Repositories\Serializers;

use League\Fractal\Serializer\ArraySerializer;

/**
 * When including collections inside a resource, do not nest under a resource
 * key unless specified. This prevents the default `data` resource key from being
 * used.
 */
class ArrayWithIncludesSerializer extends ArraySerializer
{
    public function collection($resourceKey, array $data): array
    {
        return $resourceKey ? [$resourceKey => $data] : $data;
    }
}
