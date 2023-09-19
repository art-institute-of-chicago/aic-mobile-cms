<?php

return [
    'dashboard' => [
        'modules' => [
            \App\Models\Selector::class => [
                'name' => 'selectors',
                'activity' => true,
                'count' => true,
                'create' => true,
                'search' => true,
                'search_fields' => ['number'],
            ],
            \App\Models\Stop::class => [
                'name' => 'stops',
                'activity' => true,
                'count' => true,
                'create' => true,
                'draft' => true,
                'search' => true,
            ],
            \App\Models\Tour::class => [
                'name' => 'tours',
                'activity' => true,
                'count' => true,
                'create' => true,
                'draft' => true,
                'search' => true,
            ],
        ],
    ],
    'locale' => 'en',
];
