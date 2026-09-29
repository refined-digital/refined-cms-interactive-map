<?php

namespace RefinedDigital\InteractiveMap\Module\Http\Repositories;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RefinedDigital\CMS\Modules\Core\Http\Repositories\CoreRepository;
use RefinedDigital\InteractiveMap\Module\Models\MapCategory;
use RefinedDigital\InteractiveMap\Module\Models\MapDistance;

class InteractiveMapRepository extends CoreRepository
{
    public function __construct()
    {
        $this->setModel('RefinedDigital\InteractiveMap\Module\Models\Map');
    }

    public function getCategoriesForSelect()
    {
      $types = MapCategory::whereActive(1)->orderBy('position')->get();
      $options = [];
      foreach ($types as $type) {
        $options[$type->id] = $type->name;
      }

      return $options;
    }

    public function getMarkersForFront()
    {
      $data = MapCategory::with(['markers' => function($q) {
            $q->whereActive(1)
              ->orderBy('position','asc');
          }])
          ->select(['id','name'])
          ->whereActive(1)
          ->orderBy('position')
          ->get()
      ;

      $categories = collect([]);
      if ($data->count()) {
          foreach ($data as $category) {
              $markers = collect([]);
              foreach ($category->markers as $marker) {
                  $item = new \stdClass();
                  $item->id = $marker->id;
                  $item->name = $marker->name;
                  $item->latitude = $marker->latitude;
                  $item->longitude = $marker->longitude;
                  $markers->push($item);
              }
              $cat = new \stdClass();
              $cat->id = $category->id;
              $cat->name = $category->name;
              $cat->markers = $markers;
              $categories->push($cat);
          }
      }

      return $categories;
    }

    public function getDistances()
    {
        return MapDistance::active()->order()->get();
    }

    /**
     * The main marker's position from the map settings
     *
     * @return array{lat: float, lng: float}|null
     */
    public function getMainMarker(): ?array
    {
        $settings = settings()->getKeyValue('interactive-map');
        $lat = $settings['main_marker_latitude'] ?? null;
        $lng = $settings['main_marker_longitude'] ?? null;

        return $lat && $lng ? ['lat' => (float) $lat, 'lng' => (float) $lng] : null;
    }

    /**
     * What map-markers.js needs to draw a route map: the map look and main marker
     * from the settings, plus where the route starts and the route itself
     *
     * @param array{name: string, query: string, position?: array{lat: float, lng: float}}|null $origin query is what
     *        google maps searches for, position is where the pin goes (else the route's start)
     * @param array{duration: string, distance: string, polyline: string}|null $route
     * @return array<string, mixed>|null null without a main marker to map
     */
    public function getRouteMapData(?array $origin = null, ?array $route = null): ?array
    {
        $destination = $this->getMainMarker();
        if (!$destination) {
            return null;
        }

        $settings = settings()->getKeyValue('interactive-map');
        $defaultIcon = asset('vendor/refined/interactive-map/img/marker.png');

        return [
            'apiKey' => config('interactive-map.api_key'),
            'styles' => ($settings['map_styles'] ?? null) ? json_decode($settings['map_styles']) : null,
            'destination' => [
                'name' => ($settings['main_marker_name'] ?? null) ?: config('app.name'),
                'position' => $destination,
                'icon' => $settings['main_marker_icon']->url ?? $defaultIcon,
            ],
            'origin' => $origin ? $origin + ['icon' => $settings['marker_icon']->url ?? $defaultIcon] : null,
            'route' => $origin ? $route : null,
        ];
    }

    /**
     * The driving route between two points, cached forever. The points are the
     * cache key, so moving either end naturally fetches a fresh route
     *
     * @param string|array{lat: float, lng: float} $origin an address or a position
     * @param array{lat: float, lng: float} $destination
     * @return array{duration: string, distance: string, polyline: string}|null
     */
    public function route(string|array $origin, array $destination): ?array
    {
        $key = 'interactive-map.route.'.md5(json_encode([$origin, $destination]));

        if ($route = Cache::get($key)) {
            return $route;
        }

        // failures aren't cached, so the next view tries again
        $route = $this->fetchRoute($origin, $destination);
        if ($route) {
            Cache::forever($key, $route);
        }

        return $route;
    }

    /**
     * @param string|array{lat: float, lng: float} $origin
     * @param array{lat: float, lng: float} $destination
     * @return array{duration: string, distance: string, polyline: string}|null
     */
    protected function fetchRoute(string|array $origin, array $destination): ?array
    {
        $waypoint = fn (string|array $point) => is_array($point)
            ? ['location' => ['latLng' => ['latitude' => $point['lat'], 'longitude' => $point['lng']]]]
            : ['address' => $point];

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'X-Goog-Api-Key' => config('interactive-map.api_key'),
                    'X-Goog-FieldMask' => 'routes.localizedValues,routes.polyline.encodedPolyline',
                    // the key is shared with the browser maps, so it's usually referrer restricted
                    'Referer' => config('app.url'),
                ])
                ->post('https://routes.googleapis.com/directions/v2:computeRoutes', [
                    'origin' => $waypoint($origin),
                    'destination' => $waypoint($destination),
                    'travelMode' => 'DRIVE',
                    // a typical drive rather than live traffic, as the result is cached
                    'routingPreference' => 'TRAFFIC_UNAWARE',
                    'languageCode' => config('interactive-map.route_language'),
                ]);
        } catch (ConnectionException $e) {
            Log::warning('interactive map route failed', ['error' => $e->getMessage()]);

            return null;
        }

        $route = $response->json('routes.0');
        if ($response->failed() || !$route) {
            Log::warning('interactive map route failed', ['status' => $response->status(), 'body' => $response->json()]);

            return null;
        }

        return [
            'duration' => $route['localizedValues']['duration']['text'] ?? '',
            'distance' => $route['localizedValues']['distance']['text'] ?? '',
            'polyline' => $route['polyline']['encodedPolyline'] ?? '',
        ];
    }
}
