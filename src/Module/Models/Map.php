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
        'active', 'position', 'name', 'eyebrow', 'heading', 'latitude','longitude','content','map_category_id'
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
                'name' => 'Settings',
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
                'name' => 'Attributes',
                'fields' => [
                  [
                    [ 'label' => 'Category', 'name' => 'map_category_id', 'required' => true, 'type' => 'select', 'options' => [] ],
                  ],
                  [
                    [ 'label' => 'Active', 'name' => 'active', 'required' => true, 'type' => 'select', 'options' => [1 => 'Yes', 0 => 'No'] ],
                  ],
                  [
                    [ 'label' => 'Latitude', 'name' => 'latitude', 'required' => true , ],
                  ],
                  [
                    [ 'label' => 'Longitude', 'name' => 'longitude', 'required' => true , ],
                  ],
                ]
              ]
            ]
          ],
        ]
      ],
    ];

    /**
     * The marker's copy on its category page, so only shown under the settings
     * when pages are enabled
     *
     * @var array
     */
    protected $contentBlock = [
      'name' => 'Content',
      'fields' => [
        [
          [ 'label' => 'Eyebrow', 'name' => 'eyebrow', 'required' => false ],
        ],
        [
          [ 'label' => 'Heading', 'name' => 'heading', 'required' => false, 'type' => 'textarea', 'note' => 'For italic text, wrap in <code>_</code>, ie <code>_text_</code>' ],
        ],
        [
          [ 'label' => 'Content', 'name' => 'content', 'required' => false, 'type' => 'richtext' ],
        ],
      ],
    ];

    protected static function booted(): void
    {
      // work the route out as soon as the marker moves, so the first visitor
      // to its category page never waits on google
      static::saved(function (Map $marker) {
        if (config('interactive-map.pages') && ($marker->wasRecentlyCreated || $marker->wasChanged(['latitude', 'longitude']))) {
          $marker->route();
        }
      });
    }

    public function setFormFields()
    {
      $fields = $this->formFields;
      $repo = new InteractiveMapRepository();
      $options = $repo->getCategoriesForSelect();
      $fields[0]['sections']['right']['blocks'][0]['fields'][0][0]['options'] = $options;

      if (config('interactive-map.pages')) {
        $fields[0]['sections']['left']['blocks'][] = $this->contentBlock;
      }

      return $fields;
    }

    public function category()
    {
      return $this->belongsTo(MapCategory::class, 'map_category_id');
    }

    /**
     * The driving route from this marker to the main marker
     *
     * @return array{duration: string, distance: string, polyline: string}|null
     */
    public function route(): ?array
    {
      $destination = interactiveMap()->getMainMarker();

      if (!$destination || !$this->latitude || !$this->longitude) {
        return null;
      }

      return interactiveMap()->route(
        ['lat' => (float) $this->latitude, 'lng' => (float) $this->longitude],
        $destination
      );
    }
}
