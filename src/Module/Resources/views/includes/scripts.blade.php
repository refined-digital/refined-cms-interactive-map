
@section('scripts')
  <script src="{{ asset('vendor/refined/interactive-map/js/map.js') }}"></script>
  <script defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&callback=initMap"></script>
  <script>
    const data = {
      elementId: 'map__holder',
      lat: {{ $lat }},
      lng: {{ $lng }},
      data: {!! json_encode($categories) !!}
    };

    function initMap() {
      window.interactiveMap(data);
    }
  </script>
@append
