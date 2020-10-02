@php
  $categories = interactiveMap()->getMarkersForFront();
  $mapSettings = settings()->get('interactive-map');
  $lat = isset($mapSettings->default_latitude) && $mapSettings->default_latitude->value ? $mapSettings->default_latitude->value : -34.8096626;
  $lng = isset($mapSettings->default_longitude) && $mapSettings->default_longitude->value ? $mapSettings->default_longitude->value : 138.6369941;
@endphp

<div class="map">
  <div class="holder">
    <div class="map__left">
      <div id="map__holder"></div>
    </div>
    <div class="map__right">
      @if (isset($mapSettings->heading) && $mapSettings->heading->value)
        <h3 class="heading">{{ $mapSettings->heading->value }}</h3>
      @endif
      @if (isset($mapSettings->content) && $mapSettings->content->value)
        <div class="map__content">{!! $mapSettings->content->value !!}</div>
      @endif

      @if($categories->count())
        <nav class="map__categories">
          <ul>
            @foreach ($categories as $cat)
              <li data-id="{{ $cat->id }}">{{ $cat->name }}</li>
            @endforeach
          </ul>
        </nav>
      @endif

      @if (isset($mapSettings->map_link) && $mapSettings->map_link->value)
      <footer class="content-block__footer">
        <a href="{{ $mapSettings->map_link->value }}" class="button" target="_blank">Open in Google Maps</a>
      </footer>
      @endif

    </div>
  </div>
</div>

@section('styles')
  <link href="{{ asset('vendor/refined/interactive-map/css/map.css?v='.uniqid()) }}" rel="stylesheet">
@append

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
