/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/map.js":
/*!*****************************!*\
  !*** ./resources/js/map.js ***!
  \*****************************/
/***/ (() => {

interactiveMap = function interactiveMap(options) {
  var setZoom = function setZoom(bounds) {
    map.setCenter(bounds.getCenter());
    map.fitBounds(bounds);
  };

  var openInfoWindow = null;
  var config = {
    center: {
      lat: options.lat,
      lng: options.lng
    },
    zoom: 12
  };

  if (options.mapStyles) {
    config.styles = options.mapStyles;
  }

  console.log(options);
  var markers = [];
  var map = new window.google.maps.Map(document.getElementById(options.elementId), config);
  var bounds = new window.google.maps.LatLngBounds();
  options.data.forEach(function (category) {
    category.markers.forEach(function (mark, index) {
      var markerOptions = {
        map: map,
        meta: {
          categoryId: category.id
        },
        title: mark.name,
        position: {
          lat: parseFloat(mark.latitude),
          lng: parseFloat(mark.longitude)
        },
        zIndex: index
      };

      if (options.marker.icon) {
        markerOptions.icon = {
          url: options.marker.icon
        };
      }

      var marker = new google.maps.Marker(markerOptions);
      markers.push(marker);
      bounds.extend(marker.getPosition());
      var infoWindow = new google.maps.InfoWindow({
        content: "\n          <div class=\"maps__info-window\">\n            <h4 class=\"maps__info-window-heading\">".concat(mark.name, "</h4>\n            ").concat(mark.content ? "<div class=\"maps__info-window-content\">".concat(mark.content, "</div>") : '', "\n          </div>\n        ")
      });
      marker.addListener('click', function () {
        if (openInfoWindow) {
          openInfoWindow.close();
        }

        infoWindow.open(map, marker);
        openInfoWindow = infoWindow;
      });
    });
  });

  if (options.masterIcon.show) {
    // add the master icon
    var master = new google.maps.Marker({
      map: map,
      meta: {
        categoryId: '*'
      },
      title: options.masterIcon.name,
      position: {
        lat: options.masterIcon.latitude,
        lng: options.masterIcon.longitude
      },
      icon: {
        url: options.masterIcon.icon
      },
      zIndex: markers.length + 1
    });
    markers.push(master);
    bounds.extend(master.getPosition());
    var infoWindow = new google.maps.InfoWindow({
      content: "\n        <div class=\"maps__info-window\">\n          <h4 class=\"maps__info-window-heading\">".concat(options.masterIcon.name, "</h4>\n        </div>\n      ")
    });
    master.addListener('click', function () {
      if (openInfoWindow) {
        openInfoWindow.close();
      }

      infoWindow.open(map, master);
      openInfoWindow = infoWindow;
    });
  }

  setZoom(bounds);
  var categories = document.querySelectorAll('.map__categories li');
  var klass = 'map__category-item--active';
  categories.forEach(function (cat) {
    cat.addEventListener('click', function () {
      categories.forEach(function (cat) {
        cat.classList.remove(klass);
      });
      cat.classList.add(klass);
      var bounds = new window.google.maps.LatLngBounds();
      var id = parseInt(this.dataset.id, 10);
      markers.forEach(function (marker) {
        var visible = marker.meta.categoryId === id || marker.meta.categoryId === '*';

        if (visible) {
          bounds.extend(marker.getPosition());
        }

        marker.setVisible(visible);
      });
      setZoom(bounds);
    });
  });
};

/***/ }),

/***/ "./resources/sass/map.scss":
/*!*********************************!*\
  !*** ./resources/sass/map.scss ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					result = fn();
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/assets/js/map": 0,
/******/ 			"assets/css/map": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			for(moduleId in moreModules) {
/******/ 				if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 					__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 				}
/******/ 			}
/******/ 			if(runtime) var result = runtime(__webpack_require__);
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkIds[i]] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunk_refineddigital_cms_interactive_map"] = self["webpackChunk_refineddigital_cms_interactive_map"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["assets/css/map"], () => (__webpack_require__("./resources/js/map.js")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["assets/css/map"], () => (__webpack_require__("./resources/sass/map.scss")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;