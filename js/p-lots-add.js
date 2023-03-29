let map;

// function initMap() {
//   const myLatlng =   { lat: 13.756896, lng:  121.07 };
//   const map = new google.maps.Map(document.getElementById("map"), {
//     zoom: 13,
//     center: myLatlng,
//   });

//   map.addListener("click", (mapsMouseEvent) => {
//     console.log(mapsMouseEvent.latLng.toJSON())
//     let mod = $("#addParkingLot")
//     mod.modal("show")
//   });
// }

function initAutocomplete() {
  map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: 13.756896, lng:  121.07 },
    zoom: 14,
    mapTypeId: "roadmap",
  });
  // Create the search box and link it to the UI element.
  const input = document.getElementById("pac-input");
  const searchBox = new google.maps.places.SearchBox(input);

  // map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
  // Bias the SearchBox results towards current map's viewport.
  map.addListener("bounds_changed", () => {
    searchBox.setBounds(map.getBounds());
  });
  

  let markers = [];

  // [START maps_places_searchbox_getplaces]
  // Listen for the event fired when the user selects a prediction and retrieve
  // more details for that place.
  searchBox.addListener("places_changed", () => {
    const places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }

    // Clear out the old markers.
    markers.forEach((marker) => {
      marker.setMap(null);
    });
    markers = [];

    // For each place, get the icon, name and location.
    const bounds = new google.maps.LatLngBounds();
    places.forEach((place) => {
      if (!place.geometry || !place.geometry.location) {
        console.log("Returned place contains no geometry");
        return;
      }


      const icon = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25),
      };

      // Create a marker for each place.

      let m = new google.maps.Marker({
        map,
        title: place.name,
        position: place.geometry.location,
      })


      markers.push(m);

      m.addListener("click", (mapsMouseEvent) => {
        let mod = $("#addParkingLot")
        mod.modal("show")
        mod.find("#name").val(place.name)
        mod.find("[name=placeId]").val(place.place_id)
        mod.find("#address").val(place.formatted_address)
        mod.find("[name=lat]").val(place.geometry.location.lat())
        mod.find("[name=lng]").val(place.geometry.location.lng())
      });

      
      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });
    map.fitBounds(bounds);
  });
  // [END maps_places_searchbox_getplaces]
}

window.initAutocomplete = initAutocomplete;


