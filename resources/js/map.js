interactiveMap = function(options) {
  let openInfoWindow;

  const setZoom = (bounds, map) => {
    map.setCenter(bounds.getCenter());
    map.fitBounds(bounds);
  }

  const loadMarkers = (data, map, bounds, icon = false) => {
    const markers = [];
    data.forEach(category => {
      if (!category.markers.length) {
        return;
      }

      category.markers.forEach((pin, index) => {
        const position = new window.google.maps.LatLng(parseFloat(pin.latitude), parseFloat(pin.longitude))
        const markerConfig = {
          map,
          title: pin.name,
          position,
          zIndex: index,
          meta: {
            categoryId: category.id,
            content: pin.content,
            id: pin.id
          },
        }

        if (pin.label) {
          markerConfig.label = {
            text: pin.label.text
          }

          if (pin.label.color) {
            markerConfig.label.color = pin.label.color;
          }

          if (pin.label.className) {
            markerConfig.label.className = pin.label.className;
          }

          if (pin.label.fontSize) {
            markerConfig.label.fontSize = pin.label.fontSize;
          }

          if (pin.label.fontWeight) {
            markerConfig.label.fontWeight = pin.label.fontWeight;
          }

          if (pin.label.fontFamily) {
            markerConfig.label.fontFamily = pin.label.fontFamily;
          }

        }

        if (icon && icon.icon) {
          markerConfig.icon = {
            url: icon.icon
          }
        }

        const marker = new window.google.maps.Marker(markerConfig);
        markers.push(marker);
        bounds.extend(position);
      })
    })

    return markers ;
  }

  const loadMasterMarker = (data, map, bounds, zIndex) => {
    const position = new window.google.maps.LatLng(parseFloat(data.latitude), parseFloat(data.longitude))
    const master =  new window.google.maps.Marker({
      map,
      meta: {
        categoryId: '*',
        id: '*',
      },
      title: data.name,
      position,
      icon: {
        url: data.icon
      },
      zIndex
    });

    bounds.extend(position);

    return master;
  }

  const setInfoWindow = (marker, map) => {
    const infoWindow = new window.google.maps.InfoWindow({
      content: `
        <div class="maps__info-window">
          <h4 class="maps__info-window-heading">${marker.title }</h4>
          ${marker.meta.content ? `<div class="maps__info-window-content">${marker.meta.content}</div>` : ''}
        </div>
      `
    })

    marker.addListener('click', function() {
      if (openInfoWindow) {
        openInfoWindow.close();
      }
      infoWindow.open(map, marker);
      openInfoWindow = infoWindow;
    })
  }


  const setMarkers = function(data, map, klass, metaKey, setZoom) {
    data.forEach(item => {
      item.addEventListener('click', function (e) {
        const element = e.target.closest('li');
        if (
            element &&
            element.classList.contains('map__marker-item') &&
            options.scrollIntoView &&
            options.mobileAt && window.innerWidth <= options.mobileAt
        ) {
          document.querySelector('#map__holder').scrollIntoView();
        }

        data.forEach(itm => {
          if (itm.dataset.id !== item.dataset.id) {
            itm.classList.remove(klass)
          }
        })

        const close = item.classList.contains(klass);

        if (close) {
          item.classList.remove(klass)
        } else {
          item.classList.add(klass)
        }

        const bounds = new window.google.maps.LatLngBounds();
        const id = parseInt(this.dataset.id, 10);
        const inBounds = [];
        markers.forEach(marker => {
          const visible = marker.meta[metaKey] === id || marker.meta[metaKey] === '*' || close;
          if (visible) {
            bounds.extend(marker.getPosition());
            inBounds.push(marker);
          }
          marker.setVisible(visible)
          if (options.showMasterIconOnMarkerZoom && marker.meta[metaKey] === '*') {
            marker.setVisible(true);
          }
        })

        setZoom(bounds, map);

        if (inBounds.length === 1) {
          const listener = window.google.maps.event.addListener(map, "idle", function() {
            map.setZoom(options.pinZoomLevelLimit || 18);
            window.google.maps.event.removeListener(listener);
          });
        }
      });
    })
  }


  if (!options.data.length) {
    return;
  }

  const config = {
    center: { lat: options.lat, lng: options.lng, },
    zoom: 12,
  }

  if (options.mapStyles) {
    config.styles = options.mapStyles;
  }

  const map = new window.google.maps.Map(document.getElementById(options.elementId), config);
  const bounds = new window.google.maps.LatLngBounds();

  // load the initial markers
  const markers = loadMarkers(options.data, map, bounds, options.marker);

  // load the main marker
  if (options.masterIcon.show) {
    const mainMarker = loadMasterMarker(options.masterIcon, map, bounds, markers.length + 1);
    markers.push(mainMarker);
  }

  // add the info windows
  markers.forEach(marker => setInfoWindow(marker, map));

  const categories = document.querySelectorAll(options.categorySelector);
  const categoryClass = `${options.categorySelector.replace('.', '')}--active`;
  setMarkers(categories, map, categoryClass, 'categoryId', setZoom)

  const markerItems = document.querySelectorAll(options.markerSelector);
  const markerClass = `${options.markerSelector.replace('.', '')}--active`;
  setMarkers(markerItems, map, markerClass, 'id', setZoom)

  setZoom(bounds, map);
}
