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
