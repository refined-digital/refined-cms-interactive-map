<?php

Route::namespace('InteractiveMap\Http\Controllers')
    ->group(function() {
        Route::resource('interactive-map', 'InteractiveMapController');
        Route::resource('interactive-map-categories', 'InteractiveMapCategoryController');
    })
;
