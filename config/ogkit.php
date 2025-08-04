<?php

return [
    'driver' => env('OGKIT_DRIVER', 'local'),
    'disk'   => env('OGKIT_DISK', 'local'),

    'render_route' => env('OGKIT_RENDER_ROUTE', null),

    'hosted' => [
        'endpoint' => env('OGKIT_ENDPOINT', 'https://cdn.ogkit.app/v1/image'),
        'tenant'   => env('OGKIT_TENANT', ''),
        'secret'   => env('OGKIT_SECRET', ''),
    ],
];
