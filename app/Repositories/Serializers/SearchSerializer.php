<?php

namespace App\Repositories\Serializers;

class SearchSerializer
{
    public function serialize($objects)
    {
        $suggestedObjectSerializer = new SuggestedObjectSerializer();
        return [
            'search' => array_merge(
                [
                    'search_strings' => [
                        'Essentials',
                        'Impressionism',
                        'A Sunday on La Grande Jatte - 1884',
                    ]
                ],
                $suggestedObjectSerializer->serialize($objects),
            )
        ];
    }
}
