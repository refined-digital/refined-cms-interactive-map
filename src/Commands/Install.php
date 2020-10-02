<?php

namespace RefinedDigital\Team\Commands;

use Illuminate\Console\Command;
use Validator;
use Artisan;
use DB;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refinedCMS:install-interactive-map';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs the interactive map files';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->migrate();
        $this->seed();
        $this->publish();
        $this->copyTemplates();
        $this->createSymLink();
        $this->info('Interactive Map has been successfully installed');
    }


    protected function migrate()
    {
        $this->output->writeln('<info>Migrating the database</info>');
        Artisan::call('migrate', [
            '--path' => 'vendor/refineddigital/cms-interactive-map/src/Database/Migrations',
            '--force' => 1,
        ]);
    }

    protected function seed()
    {
        $this->output->writeln('<info>Seeding the database</info>');
        Artisan::call('db:seed', [
            '--class' => '\\RefinedDigital\\InteractiveMap\\Database\\Seeds\\MapDatabaseSeeder',
            '--force' => 1
        ]);
    }

    protected function publish()
    {
        Artisan::call('vendor:publish', [
            '--tag' => 'interactive-map',
        ]);
    }

    protected function copyTemplates()
    {
        $this->output->writeln('<info>Copying Templates</info>');
        $this->copy('templates/');
        $this->copy('templates/includes');
    }

    protected function copy($resourceDir)
    {
        $dir = base_path('vendor/refineddigital/cms-interactive-map/resources/views');
        $templates = scandir($dir.$resourceDir);
        array_shift($templates);array_shift($templates);

        if (sizeof($templates)) {
            try {
                foreach ($templates as $template) {
                    $contents = file_get_contents($dir.$template);
                    file_put_contents(resource_path($resourceDir.$template), $contents);
                }
            } catch(\Exception $e) {
                $this->output->writeln('<error>Failed to copy all assets</error>');
            }
        }
    }

    protected function createSymLink()
    {
        $this->output->writeln('<info>Creating Symlink</info>');
        try {
            $link = getcwd().'/public/vendor/';
            $target = '../../../vendor/refineddigital/cms-interactive-map/assets/';

            // create the directories
            if (!is_dir($link)) {
                mkdir($link);
            }
            $link .= 'refined/';
            if (!is_dir($link)) {
                mkdir($link);
            }
            $link .= 'interactive-map';
            if (! windows_os()) {
                return symlink($target, $link);
            }

            $mode = is_dir($target) ? 'J' : 'H';

            exec("mklink /{$mode} \"{$link}\" \"{$target}\"");
        } catch(\Exception $e) {
            $this->output->writeln('<error>Failed to install symlink</error>');
        }
    }

}
