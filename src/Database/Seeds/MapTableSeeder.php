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
          [ 'name' => 'Map Styles', 'value' => '{"note": null, "content": "[\n    {\n        \"featureType\": \"all\",\n        \"elementType\": \"geometry.fill\",\n        \"stylers\": [\n            {\n                \"weight\": \"2.00\"\n            }\n        ]\n    },\n    {\n        \"featureType\": \"all\",\n        \"elementType\": \"geometry.stroke\",\n        \"stylers\": [\n            {\n                \"color\": \"#9c9c9c\"\n            }\n        ]\n    },\n    {\n        \"featureType\": \"all\",\n        \"elementType\": \"labels.text\",\n        \"stylers\": [\n            {\n                \"visibility\": \"on\"\n            }\n        ]\n    },\n    {\n        \"featureType\": \"landscape\",\n        \"elementType\": \"all\",\n        \"stylers\": [\n            {\n                \"color\": \"#f2f2f2\"\n            }\n        ]\n    },\n    {\n        \"featureType\": \"landscape\",\n        \"elementType\": \"geometry.fill\",\n        \"stylers\": [\n            {\n                \"color\": \"#ffffff\"\n            }\n        ]\n    },\n    {\n        \"featureType\": \"landscape.man_made\",\n        \"elementType\": \"geometry.fill\",\n        \"stylers\": [\n            {\n                \"color\": \"#ffffff\"\n            }\n        ]\n    },\n    {\n        \"featureType\": \"poi\",\n        \"elementType\": \"all\",\n        \"stylers\": [\n            {\n                \"visibility\": \"off\"\n            }\n        ]\n    },\n    {\n        \"featureType\": \"road\",\n        \"elementType\": \"all\",\n        \"stylers\": [\n            {\n                \"saturation\": -100\n            },\n            {\n                \"lightness\": 45\n            }\n        ]\n    },\n    {\n        \"featureType\": \"road\",\n        \"elementType\": \"geometry.fill\",\n        \"stylers\": [\n            {\n                \"color\": \"#eeeeee\"\n            }\n        ]\n    },\n    {\n        \"featureType\": \"road\",\n        \"elementType\": \"labels.text.fill\",\n        \"stylers\": [\n            {\n                \"color\": \"#7b7b7b\"\n            }\n        ]\n    },\n    {\n        \"featureType\": \"road\",\n        \"elementType\": \"labels.text.stroke\",\n        \"stylers\": [\n            {\n                \"color\": \"#ffffff\"\n            }\n        ]\n    },\n    {\n        \"featureType\": \"road.highway\",\n        \"elementType\": \"all\",\n        \"stylers\": [\n            {\n                \"visibility\": \"simplified\"\n            }\n        ]\n    },\n    {\n        \"featureType\": \"road.arterial\",\n        \"elementType\": \"labels.icon\",\n        \"stylers\": [\n            {\n                \"visibility\": \"off\"\n            }\n        ]\n    },\n    {\n        \"featureType\": \"transit\",\n        \"elementType\": \"all\",\n        \"stylers\": [\n            {\n                \"visibility\": \"off\"\n            }\n        ]\n    },\n    {\n        \"featureType\": \"water\",\n        \"elementType\": \"all\",\n        \"stylers\": [\n            {\n                \"color\": \"#46bcec\"\n            },\n            {\n                \"visibility\": \"on\"\n            }\n        ]\n    },\n    {\n        \"featureType\": \"water\",\n        \"elementType\": \"geometry.fill\",\n        \"stylers\": [\n            {\n                \"color\": \"#c8d7d4\"\n            }\n        ]\n    },\n    {\n        \"featureType\": \"water\",\n        \"elementType\": \"labels.text.fill\",\n        \"stylers\": [\n            {\n                \"color\": \"#070707\"\n            }\n        ]\n    },\n    {\n        \"featureType\": \"water\",\n        \"elementType\": \"labels.text.stroke\",\n        \"stylers\": [\n            {\n                \"color\": \"#ffffff\"\n            }\n        ]\n    }\n]", "options": [], "page_content_type_id": 2}', ],
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
