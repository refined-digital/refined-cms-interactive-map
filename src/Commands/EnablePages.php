<?php

namespace RefinedDigital\InteractiveMap\Commands;

use Artisan;
use File;
use Illuminate\Console\Command;
use RefinedDigital\CMS\Modules\Core\Models\Uri;
use RefinedDigital\InteractiveMap\Module\Models\MapCategory;

class EnablePages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refinedCMS:interactive-map-pages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gives each map category its own page, listing its markers';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $baseUrl = trim($this->ask('What url should the category pages sit under?', config('interactive-map.base_url')), '/');

        $this->writeConfig($baseUrl);

        $this->output->writeln('<info>Migrating the database</info>');
        Artisan::call('migrate', ['--force' => true]);

        $this->installContentBlock();
        $this->createCategoryUris();

        $this->info('Map category pages are enabled at /'.$baseUrl.'/{category}');
        $this->line('Add the Map Markers content block to each category page to list its markers');

        return self::SUCCESS;
    }

    /**
     * Turns pages on in the published config, publishing it first if needed
     */
    protected function writeConfig(string $baseUrl): void
    {
        $file = config_path('interactive-map.php');

        if (!is_file($file)) {
            Artisan::call('vendor:publish', ['--tag' => 'interactive-map']);
        }

        $config = file_get_contents($file);
        $values = [
            'pages' => 'true',
            'base_url' => "'".$baseUrl."'",
        ];

        foreach ($values as $key => $value) {
            $pattern = "/'".$key."'\s*=>\s*[^,]+,/";

            // older published configs predate the page settings
            $config = preg_match($pattern, $config)
                ? preg_replace($pattern, "'".$key."' => ".$value.',', $config)
                : preg_replace('/\];\s*$/', "    '".$key."' => ".$value.",\n];\n", $config);
        }

        file_put_contents($file, $config);

        // the rest of this command runs against the new settings
        config(['interactive-map.pages' => true, 'interactive-map.base_url' => $baseUrl]);
    }

    protected function installContentBlock(): void
    {
        $this->output->writeln('<info>Installing the Map Markers content block</info>');

        $defaults = __DIR__.'/defaults/';

        File::copyDirectory($defaults.'app/RefinedCMS/Content/Blocks/MapMarkers', app_path('RefinedCMS/Content/Blocks/MapMarkers'));
        File::ensureDirectoryExists(resource_path('css/components'));
        File::ensureDirectoryExists(resource_path('js/components'));
        File::copy($defaults.'css/components/map-markers.css', resource_path('css/components/map-markers.css'));
        File::copy($defaults.'js/components/map-markers.js', resource_path('js/components/map-markers.js'));

        $appFile = app_path('RefinedCMS/Content/Providers/ContentServiceProvider.php');

        if (!is_file($appFile)) {
            $this->output->writeln('<error>Could not find '.$appFile.' - register MapMarkers::class manually</error>');
            return;
        }

        $appData = file_get_contents($appFile);

        // already registered (e.g. the command was re-run)
        if (str_contains($appData, 'MapMarkers::class')) {
            return;
        }

        $appData = str_replace(
            [
                '// register the content fields',
                'use Illuminate\Support\ServiceProvider;',
            ],
            [
                '// register the content fields'."\n\t\t".'$agg->register(MapMarkers::class);',
                'use App\\RefinedCMS\\Content\\Blocks\\MapMarkers\\MapMarkers;'."\nuse Illuminate\Support\ServiceProvider;",
            ],
            $appData
        );

        file_put_contents($appFile, $appData);
    }

    /**
     * Existing categories have no uri yet, and a page without one can't be
     * reached or even listed in the admin
     */
    protected function createCategoryUris(): void
    {
        $this->output->writeln('<info>Creating the category urls</info>');

        MapCategory::whereDoesntHave('meta')
            ->get()
            ->each(fn (MapCategory $category) => Uri::create([
                'name' => $category->name,
                'title' => $category->name,
                'template_id' => config('interactive-map.details_template_id', 1),
                'uriable_id' => $category->id,
                'uriable_type' => MapCategory::class,
            ]));
    }
}
