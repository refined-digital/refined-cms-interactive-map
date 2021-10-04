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
              <li class="map__category-item" data-id="{{ $cat->id }}">{{ $cat->name }}</li>
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
