<?php

return [
    'dashboard' => [
        'modules' => [
            \App\Models\LoanObject::class => [
                'name' => 'loanObjects',
                'activity' => true,
                'count' => true,
                'create' => true,
                'search' => true,
            ],
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
    'default_crops' => [
        'upload' => [
            'default' => [
                [
                    'name' => 'default',
                    'ratio' => 9 / 5,
                    'minValues' => [
                        'width' => 364,
                        'height' => 200,
                    ],
                ],
            ],
            'thumbnail' => [
                [
                    'name' => 'thumbnail',
                    'ratio' => 1,
                    'minValues' => [
                        'width' => 90,
                        'height' => 90,
                    ],
                ],
            ],
        ],
    ],
    'locale' => 'en',
];
