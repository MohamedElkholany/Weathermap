/*global $, window, document, google, navigator*/
/* exported initMap */
/* jshint latedef:nofunc */
var map, infoWindow, previousMarker, geocoder, pos;
function initMap() {
    infoWindow = new google.maps.InfoWindow;
    geocoder   = new google.maps.Geocoder ;

    var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 4,
            center: {lat: -25.363882, lng: 131.044922 }
        });
    if ($('table').length == 1) {
      if (previousMarker) { previousMarker.setMap(null);} 
        pos = {
          lat: parseInt($('#lat').val()),
          lng: parseInt($('#lng').val())          
        }
        
        placeMarkerAndPanTo(pos, map);
        infoWindow.setContent($('#address').attr('value'));
        infoWindow.open(map, previousMarker);        
    }

    map.addListener('click', function (e) {
        if (previousMarker) { previousMarker.setMap(null);} 
    pos = e.latLng;
    placeMarkerAndPanTo(pos, map);
    $('#lat').val(pos.lat());
    $('#lng').val(pos.lng());
    geocodeLatLng(geocoder, map, infoWindow);

    }); 




if ($('table').length == 0 ){
if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(function(position) {
    
        var pos = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
    infoWindow.setPosition(pos);
    placeMarkerAndPanTo(pos, map);
    geocoder.geocode({'location': pos}, function(results){
      infoWindow.setContent(results[0].formatted_address);
      $('#address').attr('value', results[0].formatted_address);  
    });
    $('#lat').val(position.coords.latitude);
    $('#lng').val(position.coords.longitude);
    infoWindow.open(map, previousMarker);
    map.setCenter(pos);
  }, function() {
    handleLocationError(true, infoWindow, map.getCenter());
  });
 } else {
  handleLocationError(false, infoWindow, map.getCenter());
}
}

  var input = document.getElementById('pac-input');
  var searchBox = new google.maps.places.SearchBox(input);
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  map.addListener('bounds_changed', function() {
    searchBox.setBounds(map.getBounds());
  });

  var markers = [];
  searchBox.addListener('places_changed', function() {
    var places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }

    // Clear out the old markers.
    markers.forEach(function(marker) {
      marker.setMap(null);
    });
    markers = [];

    // For each place, get the icon, name and location.
    var bounds = new google.maps.LatLngBounds();
    places.forEach(function(place) {
      if (!place.geometry) {
        return;
      }
      var icon = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };

      // Create a marker for each place.
      markers.push(new google.maps.Marker({
        map: map,
        icon: icon,
        title: place.name,
        position: place.geometry.location
      }));

      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });
    map.fitBounds(bounds);
  });

}
function placeMarkerAndPanTo(latLng, map) {
  previousMarker = new google.maps.Marker({
  position: latLng,
  map: map
});
  map.panTo(latLng);
} 

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  infoWindow.setPosition(pos);
  infoWindow.setContent(browserHasGeolocation ?
                        'Error: The Geolocation service failed.' :
                        'Error: Your browser doesn\'t support geolocation.');
  infoWindow.open(map);
}


function geocodeLatLng(geocoder, map, infowindow) {
  geocoder.geocode({'location': pos}, function(results, status) {
    if (status === 'OK') {
      if (results[0]) {
        map.setZoom(8);
        infowindow.setContent(results[0].formatted_address);
        $('#address').attr('value', results[0].formatted_address);        
        infowindow.open(map, previousMarker);
      } else {
        window.alert('No results found');
      }
    } else {
      window.alert('Geocoder failed due to: ' + status);
    }
  });
}
