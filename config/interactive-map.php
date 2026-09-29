<?php

return [
    'numbers_on_markers' => false,
    'pad_numbers' => false,

    'api_key' => env('GOOGLE_API_KEY'),

    /**
     * Gives each map category its own page at /{base_url}/{category}, with content
     * blocks and a marker content tab. Turned on by refinedCMS:interactive-map-pages
     */
    'pages' => false,
    'base_url' => 'location',
    'details_template_id' => 1,

    // the language the route times and distances are written in
    'route_language' => 'en-AU',
];
