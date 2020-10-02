<?php

namespace RefinedDigital\InteractiveMap\Module\Providers;

use Illuminate\Support\ServiceProvider;
use RefinedDigital\CMS\Modules\Core\Aggregates\ModuleAggregate;
use RefinedDigital\CMS\Modules\Core\Aggregates\CustomModuleRouteAggregate;

class InteractiveMapServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->addNamespace('interactive-maps', [
            __DIR__.'/../Resources/views',
            base_path().'/resources/views'
        ]);

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        app(CustomModuleRouteAggregate::class)
            ->addRouteFile('interactive-map', __DIR__.'/../Http/routes.php');

        $menuConfig = [
            'order' => 520,
            'name' => 'Map',
            'icon' => 'fas fa-map-marker',
            'route' => 'interactive-map',
            'activeFor' => ['interactive-map', 'interactive-map-categories'],
            'children' => [
                (object) [ 'name' => 'Markers', 'route' => 'interactive-map', 'activeFor' => ['interactive-map']],
                (object) [ 'name' => 'Categories', 'route' => 'interactive-map-categories', 'activeFor' => ['interactive-map-categories']],
                (object) [ 'name' => 'Settings', 'route' => ['settings.index', 'interactive-map'], 'activeFor' => ['settings']],
            ]
        ];

        app(ModuleAggregate::class)
            ->addMenuItem($menuConfig);
    }
}
