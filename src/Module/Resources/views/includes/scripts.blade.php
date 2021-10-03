
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
      data: {!! json_encode($categories) !!},
      masterIcon: {
        name: '{{ config('app.name') }}',
        latitude: {{ $lat }},
        longitude: {{ $lng }},
        icon: '{{ asset('vendor/refined/interactive-map/img/marker.png') }}',
        show: true
      }
    };

    @if (isset($mapSettings, $mapSettings->map_styles) && $mapSettings->map_styles->value)
      data.mapStyles = {!! $mapSettings->map_styles->value !!}
    @endif

    @if (isset($mapSettings, $mapSettings->marker_icon, $mapSettings->marker_icon->value) && $mapSettings->marker_icon->value->id)
      data.marker.icon = '{{ $mapSettings->marker_icon->value->link->original  }}'
    @endif

    @if (isset($mapSettings, $mapSettings->main_marker_icon, $mapSettings->main_marker_icon->value) && $mapSettings->main_marker_icon->value->id)
      data.masterIcon.icon = '{{ $mapSettings->main_marker_icon->value->link->original  }}'
      data.masterIcon.show = true
    @endif
    @if (isset($mapSettings, $mapSettings->main_marker_name) && $mapSettings->main_marker_name->value)
      data.masterIcon.name = '{{ $mapSettings->main_marker_name->value  }}'
      data.masterIcon.show = true
    @endif
    @if (isset($mapSettings, $mapSettings->main_marker_latitude) && $mapSettings->main_marker_latitude->value)
      data.masterIcon.latitude = {{ $mapSettings->main_marker_latitude->value  }}
      data.masterIcon.show = true
    @endif
    @if (isset($mapSettings, $mapSettings->main_marker_longitude) && $mapSettings->main_marker_longitude->value)
      data.masterIcon.longitude = {{ $mapSettings->main_marker_longitude->value  }}
      data.masterIcon.show = true
    @endif

    function initMap() {
      window.interactiveMap(data);
    }
  </script>
@append
