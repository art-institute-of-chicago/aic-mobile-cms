<?php

return [
    'dashboard' => [
        'modules' => [
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
