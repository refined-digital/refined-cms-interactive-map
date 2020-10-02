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
      $categories = MapCategory::with(['markers' => function($q) {
                $q->orderBy('position','asc');
              }])
              ->whereActive(1)
              ->orderBy('position')
              ->get()
      ;

      return $categories;
    }

}
