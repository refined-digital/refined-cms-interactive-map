<?php

namespace RefinedDigital\InteractiveMap\Database\Seeds;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class MapTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
          [ 'name' => 'Default Latitude', 'value' => '{"note": null, "content": "-34.8096626", "options": [], "page_content_type_id": 3}', ],
          [ 'name' => 'Default Longitude', 'value' => '{"note": null, "content": "138.6369941", "options": [], "page_content_type_id": 3}', ],
          [ 'name' => 'Map Link', 'value' => '{"note": null, "content": "https://goo.gl/maps/QptAc9aJdcCqJJzw5", "options": [], "page_content_type_id": 3}', ],
          [ 'name' => 'Heading', 'value' => '{"note": null, "content": "On your doorstep", "options": [], "page_content_type_id": 3}', ],
          [ 'name' => 'Content', 'value' => '{"note": null, "content": "<p>This is a neighbourhood buzzing with creative energy, culture, thriving businesses and world-class eateries. Enjoy a diverse and growing community on your doorstep – meet your neighbours and be the first to experience new cafes, bars, fitness, and boutique shopping.</p>", "options": [], "page_content_type_id": 1}', ],
          [ 'name' => 'Main Marker Name', 'value' => '{"note": null, "content": "", "options": [], "page_content_type_id": 3}', ],
          [ 'name' => 'Main Marker Latitude', 'value' => '{"note": null, "content": "", "options": [], "page_content_type_id": 3}', ],
          [ 'name' => 'Main Marker Longitude', 'value' => '{"note": null, "content": "", "options": [], "page_content_type_id": 3}', ],
          [ 'name' => 'Main Marker Icon', 'value' => '{"note": "Icon here must be <strong>27px wide x 35px tall</strong>", "content": "", "options": [], "page_content_type_id": 4}', ],
          [ 'name' => 'Marker Icon', 'value' => '{"note": "Icon here must be <strong>27px wide x 35px tall</strong>", "content": "", "options": [], "page_content_type_id": 4}', ],
        ];

        foreach($settings as $pos => $u) {
            $args = [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'position' => $pos,
                'required' => 0,
                'model' => 'interactive-map'
            ];
            $data = array_merge($args, $u);
            DB::table('settings')->insert($data);
        }

        $templates = [
            [
                'name'      => 'Location',
                'source'    => 'location',
                'active'    => 1,
            ],
        ];

        foreach($templates as $pos => $u) {
            $args = [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'position' => $pos,
            ];
            $data = array_merge($args, $u);
            DB::table('templates')->insert($data);
        }
    }
}
