@php
  $categories = interactiveMap()->getMarkersForFront();
  $mapSettings = settings()->get('interactive-map');
  $lat = isset($mapSettings->default_latitude) && $mapSettings->default_latitude->value ? $mapSettings->default_latitude->value : -34.8096626;
  $lng = isset($mapSettings->default_longitude) && $mapSettings->default_longitude->value ? $mapSettings->default_longitude->value : 138.6369941;
  $categorySelector = '.map__category-text';
  $markerSelector = '.map__marker-item';
  $showNumbers = config('interactive-map.numbers_on_markers');
  $padNumbers = config('interactive-map.pad_numbers');
  $labelColor = config('interactive-map.label_color');

  if ($categories->count()) {
      $c = 0;
      foreach ($categories as $category) {
          if ($category->markers->count()) {
              foreach ($category->markers as $marker) {
                  $c ++;
                  if ($c < 10 && $padNumbers) {
                      $text = str_pad($c, 2, '0', STR_PAD_LEFT);
                  } else {
                      $text = (string)$c;
                  }
                  $label = new stdClass();
                  $label->text = $showNumbers ? $text : null;
                  $label->className = 'map__marker-label';
                  $label->color = $labelColor ?? '#000';
                  $marker->label = $label;
              }
          }
      }
  }
@endphp

@include('interactive-map::includes.html')

@include('interactive-map::includes.styles')

@include('interactive-map::includes.scripts')

