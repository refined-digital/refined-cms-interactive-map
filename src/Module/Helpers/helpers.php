<?php

use RefinedDigital\InteractiveMap\Module\Http\Repositories\InteractiveMapRepository;

if (! function_exists('interactiveMap')) {
    function interactiveMap()
    {
        return app(InteractiveMapRepository::class);
    }
}
