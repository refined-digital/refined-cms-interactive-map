<?php

Route::namespace('InteractiveMap\Module\Http\Controllers')
    ->group(function() {
        Route::resource('interactive-map', 'InteractiveMapController');
        Route::resource('interactive-map-categories', 'InteractiveMapCategoryController');
    })
;
