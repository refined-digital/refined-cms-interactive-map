{{-- a route map, drawn by map-markers.js from interactiveMap()->getRouteMapData() --}}
@if ($map)
    <div class="route-map" data-route-map="{{ json_encode($map) }}"></div>

    {{-- cloned onto the map, laid out like the google directions embed --}}
    <template data-route-map-card>
        <div class="route-map__card">
            <ol class="route-map__stops">
                <li class="route-map__stop route-map__stop--origin" data-origin></li>
                <li class="route-map__stop route-map__stop--destination" data-destination></li>
            </ol>
            <p class="route-map__summary">
                @include('content::MapMarkers.Views.car')
                <strong data-duration></strong>
                <span data-distance></span>
            </p>
            <a class="route-map__more" data-directions-link target="_blank" rel="noopener">More options</a>
        </div>
    </template>
    <template data-route-map-label>
        <div class="route-map__label">
            @include('content::MapMarkers.Views.car')
            <span data-duration></span>
        </div>
    </template>
@endif
