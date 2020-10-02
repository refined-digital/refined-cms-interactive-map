<?php

namespace RefinedDigital\InteractiveMap\Module\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EloquentSortable\Sortable;
use RefinedDigital\CMS\Modules\Core\Models\CoreModel;
use RefinedDigital\InteractiveMap\Module\Http\Repositories\InteractiveMapRepository;

class Map extends CoreModel implements Sortable
{
    use SoftDeletes;

    protected $fillable = [
        'active', 'position', 'name', 'latitude','longitude','content','map_category_id'
    ];

    protected $with = [
      'category'
    ];

    /**
     * The fields to be displayed for creating / editing
     *
     * @var array
     */
    public $formFields = [
      [
        'name' => 'Content',
        'sections' => [
          'left' => [
            'blocks' => [
              [
                'name' => 'Content',
                'fields' => [
                  [
                    [ 'label' => 'Active', 'name' => 'active', 'required' => true, 'type' => 'select', 'options' => [1 => 'Yes', 0 => 'No'] ],
                    [ 'label' => 'Name', 'name' => 'name', 'required' => true , 'attrs' => ['v-model' => 'content.name', '@keyup' => 'updateSlug' ]  ],
                  ],
                  [
                    [ 'label' => 'Latitude', 'name' => 'latitude', 'required' => true , ],
                    [ 'label' => 'Longitude', 'name' => 'longitude', 'required' => true , ],
                  ],/*
                  [
                    [ 'label' => 'Content', 'name' => 'content', 'required' => false, 'type' => 'textarea' ],
                  ],*/
                ]
              ]
            ]
          ],
          'right' => [
            'blocks' => [
              [
                'name' => 'Attributes',
                'fields' => [
                  [
                    [ 'label' => 'Category', 'name' => 'map_category_id', 'required' => true, 'type' => 'select', 'options' => [] ],
                  ],
                ]
              ]
            ]
          ],
        ]
      ],
    ];

    public function setFormFields()
    {
      $fields = $this->formFields;
      $repo = new InteractiveMapRepository();
      $options = $repo->getCategoriesForSelect();
      $fields[0]['sections']['right']['blocks'][0]['fields'][0][0]['options'] = $options;
      return $fields;
    }

    public function category()
    {
      return $this->belongsTo(MapCategory::class, 'map_category_id');
    }
}
