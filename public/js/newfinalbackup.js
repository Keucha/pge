var apiKey = 'AIzaSyCzAVx1R2k2EiPNJgreLFxJKck8ricXeUA';

var map;
var drawingManager;
var placeIdArray = [];
var polylines = [];
var snappedCoordinates = [];

function initialize() {
  var mapOptions = {
    zoom: 17,
    center: {lat: -33.8667, lng: 151.1955,}
  };
  map = new google.maps.Map(document.getElementById('map'), mapOptions);

  //  var pyrmont = new google.maps.LatLng(map.center.lat(),map.center.lng());
  //    var request = {
  //   location: pyrmont,
  //   radius: '300',
  //   types: ['store']
  // };

  // service = new google.maps.places.PlacesService(map);
  // service.nearbySearch(request, callback);  
  // function callback(results, status) {
  // if (status == google.maps.places.PlacesServiceStatus.OK) {
    
  //   for (var i = 0; i < results.length; i++) {
  //     var place = results[i];
  //     if(i == 0)
  //     {
  //       var newSnap =new google.maps.LatLng(place.geometry.location.lat(),place.geometry.location.lng());
  //       snappedCoordinates.push(newSnap);
  //       placeIdArray.push(place.place_id);

  //     }

  //   }
  // }
  // }

  //  var snappedPolyline = new google.maps.Polyline({
  //   path: snappedCoordinates,
  //   strokeColor: '#FF0000',
  //   strokeWeight: 4,
  //   strokeOpacity: 0,
  // });

  // snappedPolyline.setMap(map);

  // polylines.push(snappedPolyline);


  // Adds a Places search box. Searching for a place will center the map on that
  // location.
  map.controls[google.maps.ControlPosition.RIGHT_TOP].push(
      document.getElementById('bar'));
  var autocomplete = new google.maps.places.Autocomplete(
      document.getElementById('autoc'));
  autocomplete.bindTo('bounds', map);
  autocomplete.addListener('place_changed', function() {
    var place = autocomplete.getPlace();
    if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);
    } else {
      map.setCenter(place.geometry.location);
      map.setZoom(17);
    }
  });

  // Enables the polyline drawing control. Click on the map to start drawing a
  // polyline. Each click will add a new vertice. Double-click to stop drawing.
  drawingManager = new google.maps.drawing.DrawingManager({
    drawingMode: google.maps.drawing.OverlayType.POLYLINE,
    drawingControl: true,
    drawingControlOptions: {
      position: google.maps.ControlPosition.TOP_CENTER,
      drawingModes: [
        google.maps.drawing.OverlayType.POLYLINE
      ]
    },
    polylineOptions: {
      strokeColor: '#696969',
      strokeWeight: 2,
      strokeOpacity: 0.3,
    }
  });
  drawingManager.setMap(map);

  // Snap-to-road when the polyline is completed.
  drawingManager.addListener('polylinecomplete', function(poly) {
    var path = poly.getPath();
    polylines.push(poly);
    placeIdArray = [];
    runSnapToRoad(path);
  });

    function addS(cLat,cLng)
    {
  
   var pyrmont = new google.maps.LatLng(cLat,cLng);
     var request = {
    location: pyrmont,
    radius: '300',
    types: ['store']
  };

  service = new google.maps.places.PlacesService(map);
  service.nearbySearch(request, callback);  
  function callback(results, status) {
  if (status == google.maps.places.PlacesServiceStatus.OK) {
    
    for (var i = 0; i < results.length; i++) {
      var place = results[i];
      if(i == 0)
      {
        var newSnap =new google.maps.LatLng(place.geometry.location.lat(),place.geometry.location.lng());
        snappedCoordinates.push(newSnap);
        placeIdArray.push(place.place_id);

      }

    }
  }
  }

   

    }

    map.addListener("click", (mapsMouseEvent) => {
    var cLat = mapsMouseEvent.latLng.lat();
    var cLng = mapsMouseEvent.latLng.lng();
    var cPlace = mapsMouseEvent.placeId;
    var cLatLng = cLat+","+cLng;

    var latlng = new google.maps.LatLng(cLat,cLng);
    snappedCoordinates.push(latlng);
    placeIdArray.push(cPlace);

   
addS(cLat,cLng);
  
    console.log("placeId",placeIdArray);
    console.log("Polyline",polylines);
    console.log("snapped",snappedCoordinates);

});

   


// map.addListener("click", (mapsMouseEvent) => {
//     var cLat = mapsMouseEvent.latLng.lat();
//     var cLng = mapsMouseEvent.latLng.lng();
//     var cLatLng = cLat+","+cLng;

//     $.ajax({
//       type:'GET',
//       url:'https://roads.googleapis.com/v1/nearestRoads?points='+cLat+','+cLng+'&key='+apiKey
//     }).done(function(resp){

//       for (var i = 0; i < resp.snappedPoints.length; i++) {
//     var latlng = new google.maps.LatLng(
//         resp.snappedPoints[i].location.latitude,
//         resp.snappedPoints[i].location.longitude);
//         snappedCoordinates.push(latlng);
//       }

//   var snappedPolyline = new google.maps.Polyline({
//     path: snappedCoordinates,
//     strokeColor: '#FF0000',
//     strokeWeight: 4,
//     strokeOpacity: 0,
//   });

//   snappedPolyline.setMap(map);

//   var path = snappedPolyline.getPath();
//     polylines.push(snappedPolyline);
//     placeIdArray = [];
//     runSnapToRoad(path);
  



      
//     }).fail(function(resp){

//       console.log(resp);

//     });

//     console.log("placeId",placeIdArray);
//     console.log("Polyline",polylines);
//     console.log("snapped",snappedCoordinates);

// });

  // Clear button. Click to remove all polylines.
  // document.getElementById('clear').addEventListener('click', function(event) {
  //   event.preventDefault();
  //   for (var i = 0; i < polylines.length; ++i) {
  //     polylines[i].setMap(null);
  //   }
  //   polylines = [];
  //   return false;
  // });
}

// Snap a user-created polyline to roads and draw the snapped path
function runSnapToRoad(path) {
  var pathValues = [];
  for (var i = 0; i < path.getLength(); i++) {
    pathValues.push(path.getAt(i).toUrlValue());
  }

  $.get('https://roads.googleapis.com/v1/snapToRoads', {
    interpolate: true,
    key: apiKey,
    path: pathValues.join('|')
  }, function(data) {
    processSnapToRoadResponse(data);
    drawSnappedPolyline();

  });
}

// Store snapped polyline returned by the snap-to-road service.
function processSnapToRoadResponse(data) {
  snappedCoordinates = [];
  placeIdArray = [];
  for (var i = 0; i < data.snappedPoints.length; i++) {
    var latlng = new google.maps.LatLng(
        data.snappedPoints[i].location.latitude,
        data.snappedPoints[i].location.longitude);
    snappedCoordinates.push(latlng);
    placeIdArray.push(data.snappedPoints[i].placeId);
  }
}

// Draws the snapped polyline (after processing snap-to-road response).
function drawSnappedPolyline() {
  var snappedPolyline = new google.maps.Polyline({
    path: snappedCoordinates,
    strokeColor: '#add8e6',
    strokeWeight: 4,
    strokeOpacity: 0.9,
  });

  snappedPolyline.setMap(map);
  polylines.push(snappedPolyline);
}



$(window).load(initialize);

    console.log("placeId",placeIdArray);
    console.log("Polyline",polylines);
    console.log("snapped",snappedCoordinates);