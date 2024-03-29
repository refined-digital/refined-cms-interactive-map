<?php

namespace RefinedDigital\InteractiveMap\Module\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use RefinedDigital\CMS\Modules\Core\Models\CoreModel;
use Spatie\EloquentSortable\Sortable;

class MapCategory extends CoreModel implements Sortable
{
    use SoftDeletes;

    protected $fillable = [
        'active', 'map_category_id', 'name',
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
                                    [ 'label' => 'Name', 'name' => 'name', 'required' => true , 'attrs' => ['v-model' => 'content.name', '@keyup' => 'updateSlug' ]  ],
                                ],
                            ]
                        ]
                    ]
                ],
                'right' => [
                    'blocks' => [
                        [
                            'name' => 'Settings',
                            'fields' => [
                                [
                                    [ 'label' => 'Active', 'name' => 'active', 'required' => true, 'type' => 'select', 'options' => [1 => 'Yes', 0 => 'No'] ],
                                ],
                            ]
                        ]
                    ]
                ],
            ]
        ],
    ];

    public function markers()
    {
      return $this->hasMany(Map::class);
    }
}
