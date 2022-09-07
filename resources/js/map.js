interactiveMap = function(options) {
  const setZoom = bounds => {
    map.setCenter(bounds.getCenter());
    map.fitBounds(bounds);
  }

  let openInfoWindow = null;

  const config = {
    center: { lat: options.lat, lng: options.lng, },
    zoom: 12,
  }

  if (options.mapStyles) {
    config.styles = options.mapStyles;
  }

  const markers = [];
  const map = new window.google.maps.Map(document.getElementById(options.elementId), config);
  const bounds = new window.google.maps.LatLngBounds();

  options.data.forEach(category => {
    category.markers.forEach((mark, index) => {
      const markerOptions = {
        map,
        meta: {
          categoryId: category.id,
          id: mark.id
        },
        title: mark.name,
        position: {
          lat: parseFloat(mark.latitude),
          lng: parseFloat(mark.longitude),
        },
        zIndex: index
      }

      if (mark.label) {
        markerOptions.label = {
          text: mark.label.text
        }

        if (mark.label.color) {
          markerOptions.label.color = mark.label.color;
        }

        if (mark.label.className) {
          markerOptions.label.className = mark.label.className;
        }

        if (mark.label.fontSize) {
          markerOptions.label.fontSize = mark.label.fontSize;
        }

        if (mark.label.fontWeight) {
          markerOptions.label.fontWeight = mark.label.fontWeight;
        }

        if (mark.label.fontFamily) {
          markerOptions.label.fontFamily = mark.label.fontFamily;
        }
      }

      if (options.marker.icon) {
        markerOptions.icon = {
          url: options.marker.icon
        }
      }
      const marker = new google.maps.Marker(markerOptions);


      markers.push(marker);
      bounds.extend(marker.getPosition());
      const infoWindow = new google.maps.InfoWindow({
        content: `
          <div class="maps__info-window">
            <h4 class="maps__info-window-heading">${mark.name }</h4>
            ${mark.content ? `<div class="maps__info-window-content">${mark.content}</div>` : ''}
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
    })
  })

  if (options.masterIcon.show) {
    // add the master icon
    const master =  new google.maps.Marker({
      map,
      meta: {
        categoryId: '*'
      },
      title: options.masterIcon.name,
      position: {
        lat: options.masterIcon.latitude,
        lng: options.masterIcon.longitude,
      },
      icon: {
        url: options.masterIcon.icon
      },
      zIndex: markers.length + 1
    });

    markers.push(master);
    bounds.extend(master.getPosition());
    const infoWindow = new google.maps.InfoWindow({
      content: `
        <div class="maps__info-window">
          <h4 class="maps__info-window-heading">${options.masterIcon.name}</h4>
        </div>
      `
    })
    master.addListener('click', function() {
      if (openInfoWindow) {
        openInfoWindow.close();
      }
      infoWindow.open(map, master);
      openInfoWindow = infoWindow;
    })
  }

  setZoom(bounds);

  const setMarkers = function(data, klass, metaKey) {
    data.forEach(item => {
      item.addEventListener('click', function () {
        console.log(item);
        data.forEach(itm => {
          console.log(itm);
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
        })
        setZoom(bounds);
        if (inBounds.length === 1) {
          const listener = window.google.maps.event.addListener(map, "idle", function() {
            map.setZoom(options.pinZoomLevelLimit || 18);
            window.google.maps.event.removeListener(listener);
          });
        }
      });
    })
  }

  const categories = document.querySelectorAll(options.categorySelector);
  const categoryClass = `${options.categorySelector.replace('.', '')}--active`;
  setMarkers(categories, categoryClass, 'categoryId')

  const markerItems = document.querySelectorAll(options.markerSelector);
  const markerClass = `${options.markerSelector.replace('.', '')}--active`;
  setMarkers(markerItems, markerClass, 'id')

}
