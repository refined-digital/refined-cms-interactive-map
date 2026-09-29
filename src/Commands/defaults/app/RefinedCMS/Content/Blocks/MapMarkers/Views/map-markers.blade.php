@php
    use RefinedDigital\InteractiveMap\Module\Models\MapCategory;

    // the block lists the markers of the category page it sits on
    $markers = $page instanceof MapCategory
        ? $page->markers()->whereActive(1)->orderBy('position')->get()
        : collect();

    $classes = array_merge($classes, [
      'page__block--bg-'.((isset($content->background_colour) && $content->background_colour) ? $content->background_colour : 'white'),
    ]);
@endphp
@foreach ($markers as $marker)
    @php
        $map = interactiveMap()->getRouteMapData(
            [
                'name' => $marker->name,
                'query' => $marker->latitude.','.$marker->longitude,
                'position' => ['lat' => (float) $marker->latitude, 'lng' => (float) $marker->longitude],
            ],
            $marker->route()
        );

        // shaped like block content so the standard includes render it
        $content = (object) [
            'title' => $marker->eyebrow ?: $page->name,
            'heading' => $marker->heading ?: $marker->name,
            'content' => $marker->content,
        ];
    @endphp
    <section class="{{ implode(' ', $classes) }} map-markers__marker map-markers__marker--{{ $loop->even ? 'left' : 'right' }}">
        <div class="map-markers__content">
            @include('content-templates::includes.title')
            @include('content-templates::includes.heading')
            @include('content-templates::includes.content')
        </div>
        <div class="map-markers__map-holder">
            @include('content::MapMarkers.Views.map')
        </div>
    </section>
@endforeach
