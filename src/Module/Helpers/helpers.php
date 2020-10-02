<?php

use RefinedDigital\InteractiveMap\Module\Http\Controllers\InteractiveMapRepository;

if (! function_exists('interactiveMap')) {
    function interactiveMap()
    {
        return app(InteractiveMapRepository::class);
    }
}
