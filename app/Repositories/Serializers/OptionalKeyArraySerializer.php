<?php

namespace App\Repositories\Serializers;

use League\Fractal\Serializer\ArraySerializer;

/**
 * Does not include a resource key unless specified. This prevents the default
 * `data` resource key from being used.
 */
class OptionalKeyArraySerializer extends ArraySerializer
{
    public function collection($resourceKey, array $data): array
    {
        return $resourceKey ? [$resourceKey => $data] : $data;
    }
}
