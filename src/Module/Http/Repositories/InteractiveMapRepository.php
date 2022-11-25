<?php

namespace RefinedDigital\InteractiveMap\Module\Http\Repositories;

use RefinedDigital\CMS\Modules\Core\Http\Repositories\CoreRepository;
use RefinedDigital\InteractiveMap\Module\Models\MapCategory;

class InteractiveMapRepository extends CoreRepository
{
    public function __construct()
    {
        $this->setModel('RefinedDigital\InteractiveMap\Module\Models\Map');
    }

    public function getCategoriesForSelect()
    {
      $types = MapCategory::whereActive(1)->orderBy('position')->get();
      $options = [];
      foreach ($types as $type) {
        $options[$type->id] = $type->name;
      }

      return $options;
    }

    public function getMarkersForFront()
    {
      $data = MapCategory::with(['markers' => function($q) {
            $q->whereActive(1)
              ->orderBy('position','asc');
          }])
          ->select(['id','name'])
          ->whereActive(1)
          ->orderBy('position')
          ->get()
      ;

      $categories = collect([]);
      if ($data->count()) {
          foreach ($data as $category) {
              $markers = collect([]);
              foreach ($category->markers as $marker) {
                  $item = new \stdClass();
                  $item->id = $marker->id;
                  $item->name = $marker->name;
                  $item->latitude = $marker->latitude;
                  $item->longitude = $marker->longitude;
                  $markers->push($item);
              }
              $cat = new \stdClass();
              $cat->id = $category->id;
              $cat->name = $category->name;
              $cat->markers = $markers;
              $categories->push($cat);
          }
      }

      return $categories;
    }

}
