<?php

namespace RefinedDigital\InteractiveMap\Database\Seeds;

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MapDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(MapTableSeeder::class);
    }
}
