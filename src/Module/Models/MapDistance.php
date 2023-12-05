<?php

namespace RefinedDigital\InteractiveMap\Module\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use RefinedDigital\CMS\Modules\Core\Models\CoreModel;
use Spatie\EloquentSortable\Sortable;

class MapDistance extends CoreModel implements Sortable
{
    use SoftDeletes;

    protected $fillable = [
        'active', 'name', 'distance',
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
                                    [ 'label' => 'Name', 'name' => 'name', 'required' => true  ],
                                    [ 'label' => 'Distance', 'name' => 'distance', 'required' => true],
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
}
