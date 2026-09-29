<?php

namespace RefinedDigital\InteractiveMap\Module\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use RefinedDigital\CMS\Modules\Core\Models\CoreModel;
use RefinedDigital\CMS\Modules\Pages\Traits\HasContentBlocks;
use RefinedDigital\CMS\Modules\Pages\Traits\IsPage;
use Spatie\EloquentSortable\Sortable;

class MapCategory extends CoreModel implements Sortable
{
    use SoftDeletes;

    // traits can't be conditional, so their hooks are wrapped below and only run
    // when the categories have pages
    use IsPage {
        bootIsPage as bootIsPageWhenEnabled;
    }
    use HasContentBlocks {
        bootHasContentBlocks as bootHasContentBlocksWhenEnabled;
        initializeHasContentBlocks as initializeHasContentBlocksWhenEnabled;
    }

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

    protected $contentBlocks = [
        'name' => 'Content Blocks',
        'fields' => [
            [
                ['label' => 'Content', 'name' => 'content', 'type' => 'contentBlocks', 'hideLabel' => true],
            ],
        ],
    ];

    /**
     * Whether the categories are pages, see the interactive-map.pages config
     */
    public static function hasPages(): bool
    {
        return (bool) config('interactive-map.pages');
    }

    public static function bootIsPage(): void
    {
        if (static::hasPages()) {
            static::bootIsPageWhenEnabled();
        }
    }

    public static function bootHasContentBlocks(): void
    {
        if (static::hasPages()) {
            static::bootHasContentBlocksWhenEnabled();
        }
    }

    protected function initializeHasContentBlocks(): void
    {
        // isPage is what adds the meta data tab to the form
        $this->isPage = static::hasPages();

        if ($this->isPage) {
            $this->initializeHasContentBlocksWhenEnabled();
        }
    }

    /**
     * IsPage reads the template from here when saving the page's uri
     */
    public function getTemplateIdAttribute(): int
    {
        return (int) config('interactive-map.details_template_id', 1);
    }

    public function setFormFields()
    {
        $fields = $this->formFields;

        if (static::hasPages()) {
            $fields[] = $this->contentBlocks;
        }

        return $fields;
    }

    public function markers()
    {
      return $this->hasMany(Map::class);
    }
}
