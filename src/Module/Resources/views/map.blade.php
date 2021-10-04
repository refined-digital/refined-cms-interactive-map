@php
  $categories = interactiveMap()->getMarkersForFront();
  $mapSettings = settings()->get('interactive-map');
  $lat = isset($mapSettings->default_latitude) && $mapSettings->default_latitude->value ? $mapSettings->default_latitude->value : -34.8096626;
  $lng = isset($mapSettings->default_longitude) && $mapSettings->default_longitude->value ? $mapSettings->default_longitude->value : 138.6369941;
  $categorySelector = '.map__category-item';
  $markerSelector = '.map__marker-item';
@endphp

@include('interactive-map::includes.html')

@include('interactive-map::includes.styles')

@include('interactive-map::includes.scripts')


