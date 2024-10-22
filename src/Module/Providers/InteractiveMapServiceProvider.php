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
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" fill="currentColor"><path class="fa-secondary" opacity=".4" d="M0 209.6L0 480.4c0 17 17.1 28.6 32.9 22.3L160 451.8l0-251.4c-3.5-6.9-6.7-13.8-9.6-20.6c-5.6-13.2-10.4-27.4-12.8-41.5l-122.6 49C6 191 0 199.8 0 209.6zM192 255l0 194.4 192 54.9L384 255c-20.5 31.3-42.3 59.6-56.2 77c-20.5 25.6-59.1 25.6-79.6 0c-13.9-17.4-35.7-45.7-56.2-77zm224-54.6L416 503l144.9-58c9.1-3.6 15.1-12.5 15.1-22.3L576 152c0-17-17.1-28.6-32.9-22.3l-116 46.4c-.5 1.2-1 2.5-1.5 3.7c-2.9 6.8-6.1 13.7-9.6 20.6z"/><path class="fa-primary" d="M302.8 312C334.9 271.9 408 174.6 408 120C408 53.7 354.3 0 288 0S168 53.7 168 120c0 54.6 73.1 151.9 105.2 192c7.7 9.6 22 9.6 29.6 0zM288 72a40 40 0 1 1 0 80 40 40 0 1 1 0-80z"/></svg>',
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
