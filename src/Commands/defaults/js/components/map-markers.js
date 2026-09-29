/**
 * Loads the google maps bootstrap, or waits on one already on the page (the
 * interactive map loads its own) as a second copy breaks both maps.
 */
const loadGoogleMaps = (apiKey) =>
  new Promise((resolve, reject) => {
    if (
      !document.querySelector('script[src*="maps.googleapis.com/maps/api/js"]')
    ) {
      const script = document.createElement('script');
      script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&loading=async`;
      script.onerror = reject;
      document.head.append(script);
    }

    const waitForBootstrap = () =>
      window.google?.maps?.importLibrary
        ? resolve()
        : setTimeout(waitForBootstrap, 100);
    waitForBootstrap();
  });

/**
 * Clones a template from next to the map and fills its data-* slots.
 */
const fromTemplate = (element, name, slots) => {
  const template = element.parentElement.querySelector(
    `[data-route-map-${name}]`,
  );
  const node = template.content.firstElementChild.cloneNode(true);

  Object.entries(slots).forEach(([slot, value]) => {
    node.querySelectorAll(`[data-${slot}]`).forEach((target) => {
      target.textContent = value;
    });
  });

  return node;
};

/**
 * Draws the main marker, and when there's a route, the drive to it with the
 * time and distance, laid out like google's directions embed. The route itself
 * is worked out and cached on the server.
 */
const initMap = async (element) => {
  const data = JSON.parse(element.dataset.routeMap);
  await loadGoogleMaps(data.apiKey);

  const { Map, OverlayView, Polyline } =
    await google.maps.importLibrary('maps');
  const { Marker } = await google.maps.importLibrary('marker');
  const { encoding } = await google.maps.importLibrary('geometry');

  // classic styles, not a mapId: a mapId would ignore the interactive map's styles
  const map = new Map(element, {
    center: data.destination.position,
    zoom: 14,
    disableDefaultUI: true,
    zoomControl: true,
    ...(data.styles ? { styles: data.styles } : {}),
  });

  new Marker({
    map,
    position: data.destination.position,
    title: data.destination.name,
    icon: { url: data.destination.icon },
  });

  if (!data.route?.polyline) {
    return;
  }

  const path = encoding.decodePath(data.route.polyline);

  // google's route blue, drawn twice for the darker outline
  new Polyline({
    map,
    path,
    strokeColor: '#1967d2',
    strokeWeight: 8,
    zIndex: 1,
  });
  new Polyline({
    map,
    path,
    strokeColor: '#4285f4',
    strokeWeight: 5,
    zIndex: 2,
  });

  // routes only run on roads google knows, so they can stop short of a pin (new
  // estates have no mapped streets yet). Like google maps, the pins stay on their
  // real spots with a dotted walk to the road
  const origin = data.origin.position ?? path[0];
  const walk = {
    strokeOpacity: 0,
    icons: [
      {
        icon: {
          path: google.maps.SymbolPath.CIRCLE,
          scale: 2,
          fillColor: '#4285f4',
          fillOpacity: 1,
          strokeOpacity: 0,
        },
        repeat: '8px',
      },
    ],
    zIndex: 2,
  };
  new Polyline({ map, path: [origin, path[0]], ...walk });
  new Polyline({
    map,
    path: [path[path.length - 1], data.destination.position],
    ...walk,
  });

  new Marker({
    map,
    position: origin,
    title: data.origin.name,
    icon: { url: data.origin.icon },
  });

  const card = fromTemplate(element, 'card', {
    origin: data.origin.name,
    destination: data.destination.name,
    duration: data.route.duration,
    distance: data.route.distance,
  });
  const { lat, lng } = data.destination.position;
  card.querySelector('[data-directions-link]').href =
    'https://www.google.com/maps/dir/?' +
    new URLSearchParams({
      api: 1,
      origin: data.origin.query,
      destination: `${lat},${lng}`,
    });
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(card);

  // the time bubble halfway along the route
  const label = fromTemplate(element, 'label', {
    duration: data.route.duration,
  });
  const midpoint = path[Math.floor(path.length / 2)];
  const overlay = new OverlayView();
  overlay.onAdd = () => overlay.getPanes().floatPane.append(label);
  overlay.draw = () => {
    const { x, y } = overlay.getProjection().fromLatLngToDivPixel(midpoint);
    label.style.left = `${x}px`;
    label.style.top = `${y}px`;
  };
  overlay.onRemove = () => label.remove();
  overlay.setMap(map);

  // keep the route clear of the card in the top left when there's room beside it,
  // with headroom for the pins, which stand above the points they mark
  const bounds = new google.maps.LatLngBounds();
  [origin, data.destination.position, ...path].forEach((point) =>
    bounds.extend(point),
  );
  const wide = element.offsetWidth > 700;
  map.fitBounds(bounds, {
    top: wide ? 100 : 200,
    right: 60,
    bottom: 60,
    left: wide ? 300 : 60,
  });
};

// a category page can hold a dozen of these, so each only loads once it's close
// to scrolling into view
const observer = new IntersectionObserver(
  (entries) => {
    entries
      .filter((entry) => entry.isIntersecting)
      .forEach(({ target }) => {
        observer.unobserve(target);
        initMap(target).catch((error) => console.error('route map', error));
      });
  },
  { rootMargin: '200px' },
);

document
  .querySelectorAll('[data-route-map]')
  .forEach((element) => observer.observe(element));
