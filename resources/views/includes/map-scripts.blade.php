@php
  $repo = new \App\RefinedCMS\Map\Http\Repositories\MapRepository();
  $categories = $repo->getMarkersForFront();
  $mapSettings = settings()->get('interactive-map');
  $lat = isset($mapSettings->default_latitude) && $mapSettings->default_latitude->value ? $mapSettings->default_latitude->value : -34.8096626;
  $lng = isset($mapSettings->default_longitude) && $mapSettings->default_longitude->value ? $mapSettings->default_longitude->value : 138.6369941;
@endphp
<script defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&callback=initMap"></script>
<script>
  const data = {
    elementId: 'map__holder',
    lat: {{ $lat }},
    lng: {{ $lng }},
    data: {!! json_encode($categories) !!}
  };

  function initMap() {
    gMap(data);
  }
</script>
