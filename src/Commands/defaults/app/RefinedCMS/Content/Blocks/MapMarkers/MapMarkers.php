<?php

namespace App\RefinedCMS\Content\Blocks\MapMarkers;

use RefinedDigital\CMS\Modules\Content\BaseContent;
use RefinedDigital\CMS\Modules\Content\Contracts\ContentInterface;
use RefinedDigital\CMS\Modules\Core\Aggregates\AssetAggregate;

class MapMarkers extends BaseContent implements ContentInterface
{
    protected string $name = 'Map Markers';

    protected string $description = 'Each of the map category\'s markers, with the drive to the main marker. Only shows on a map category page';

    public function __construct()
    {
        app(AssetAggregate::class)
            ->addStyle('map-markers.css')
            ->addScript('map-markers.js');
    }

    public function fields(): array
    {
        return [
            $this->getField('background'),
        ];
    }
}
