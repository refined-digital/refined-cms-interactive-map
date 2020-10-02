<?php

namespace RefinedDigital\InteractiveMap\Commands;

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
        $this->addKeyToENV();
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
        $this->copy('templates');
    }

    protected function copy($localDir)
    {
        $dir = base_path('vendor/refineddigital/cms-interactive-map/resources/views/');
        $templates = scandir($dir.$localDir);
        array_shift($templates);array_shift($templates);

        if (sizeof($templates)) {
            try {
                foreach ($templates as $template) {
                  if (!is_dir($dir.$localDir.'/'.$template)) {
                    exec('cp '.$dir.$localDir.'/'.$template.' '.resource_path('views/'.$localDir.'/'.$template));
                  }
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
            $link = public_path('vendor/');
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
            print_r($e->getMessage()); echo PHP_EOL;
            $this->output->writeln('<error>Failed to install symlink</error>');
        }
    }

    protected function addKeyToEnv()
    {
      $file = file_get_contents(app()->environmentFilePath());

      $file .= PHP_EOL.PHP_EOL.'GOOGLE_API_KEY=';

      file_put_contents(app()->environmentFilePath(), $file);
    }

}
