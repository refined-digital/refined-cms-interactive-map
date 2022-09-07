@php
  $showNumbers = config('interactive-map.numbers_on_markers');
  $padNumbers = config('interactive-map.pad_numbers');
@endphp
<div class="map flex">
  <div class="map__left fade-in-up">
    <div id="map__holder"></div>
  </div>
  <div class="map__right fade-in-up">
    @if (isset($mapSettings->heading) && $mapSettings->heading->value)
      <h3 class="heading">{{ $mapSettings->heading->value }}</h3>
    @endif
    @if (isset($mapSettings->content) && $mapSettings->content->value)
      <div class="map__content">{!! $mapSettings->content->value !!}</div>
    @endif
    @if($categories->count())
      <nav class="map__categories">
        <ul>
          @php $index = 0; @endphp
          @foreach ($categories as $cat)
            @if ($cat->markers->count())
              <li class="map__category-item">
              <span class="map__category-text title" data-id="{{ $cat->id }}">
                {{ $cat->name }}
              </span>
                <ul>
                  @foreach ($cat->markers as $marker)
                    @php $index ++ @endphp
                    <li class="map__marker-item" data-id="{{ $marker->id }}">
                    <span class="map__category-number">
                      @if ($padNumbers)
                        {{ str_pad($index, 2, '0', STR_PAD_LEFT) }}
                      @else
                        {{ $index }}
                      @endif
                    </span>
                      <span class="map__marker-text">
                      {{ $marker->name }}
                    </span>
                    </li>
                  @endforeach
                </ul>
              </li>
            @endif
          @endforeach
        </ul>
      </nav>
    @endif

    @if (isset($mapSettings->map_link) && $mapSettings->map_link->value)
      <footer class="content-block__link">
        <a href="{{ $mapSettings->map_link->value }}" class="link" target="_blank">
          Open in Google Maps
        </a>
      </footer>
    @endif

  </div>
</div>
