
@section('scripts')
  <script src="{{ asset('vendor/refined/interactive-map/js/map.js') }}"></script>
  <script defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&callback=initMap"></script>
  <script>
    const data = {
      elementId: 'map__holder',
      lat: {{ $lat }},
      lng: {{ $lng }},
      marker: {
        icon: '{{ asset('vendor/refined/interactive-map/img/marker.png') }}'
      },
      data: {!! json_encode($categories) !!}
    };

    @if (isset($mapSettings, $mapSettings->map_styles) && $mapSettings->map_styles->value)
      data.mapStyles = {!! $mapSettings->map_styles->value !!}
    @endif

    function initMap() {
      window.interactiveMap(data);
    }
  </script>
@append
