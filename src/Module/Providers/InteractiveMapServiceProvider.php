<?php

namespace RefinedDigital\InteractiveMap\Module\Providers;

use Illuminate\Support\ServiceProvider;
use RefinedDigital\CMS\Modules\Core\Aggregates\ModuleAggregate;
use RefinedDigital\CMS\Modules\Core\Aggregates\RouteAggregate;
use RefinedDigital\InteractiveMap\Commands\Install;

class InteractiveMapServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->addNamespace('interactive-map', [
            resource_path('views/interactive-map'),
            app_path('RefinedCMS/InteractiveMap'),
            __DIR__.'/../Resources/views',
        ]);

        try {
            if ($this->app->runningInConsole()) {
                if (\DB::connection()->getDatabaseName() && !\Schema::hasTable('maps')) {
                    $this->commands([
                        Install::class
                    ]);
                }
            }
        } catch (\Exception $e) {}

        $this->publishes([
            __DIR__.'/../../../config/interactive-map.php' => config_path('interactive-map.php'),
        ], 'interactive-map');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        app(RouteAggregate::class)
            ->addRouteFile('interactive-map', __DIR__.'/../Http/routes.php');

        $this->mergeConfigFrom(__DIR__.'/../../../config/interactive-map.php', 'interactive-map');
        $menuConfig = [
            'order' => 520,
            'name' => 'Map',
            'icon' => 'fas fa-map-marker',
            'route' => 'interactive-map',
            'activeFor' => ['interactive-map', 'interactive-map-categories', 'interactive-map-distance'],
            'children' => [
                (object) [ 'name' => 'Markers', 'route' => 'interactive-map', 'activeFor' => ['interactive-map']],
                (object) [ 'name' => 'Categories', 'route' => 'interactive-map-categories', 'activeFor' => ['interactive-map-categories']],
                (object) [ 'name' => 'Distances', 'route' => 'interactive-map-distance', 'activeFor' => ['interactive-map-distance']],
                (object) [ 'name' => 'Settings', 'route' => ['settings.index', 'interactive-map'], 'activeFor' => ['settings']],
            ]
        ];

        $this->mergeConfigFrom(__DIR__.'/../../../config/interactive-map.php', 'interactive-map');

        app(ModuleAggregate::class)
            ->addMenuItem($menuConfig);
    }
}
