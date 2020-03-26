@extends('layouts.app')

@section('title', 'Georeferenciación')

@section('content')

<h3 class="mb-3">Georeferenciación</h3>

<div style="width: 100%; height: 480px" id="mapContainer"></div>

@endsection

@section('custom_js')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-core.js" type="text/javascript" charset="utf-8"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-service.js" type="text/javascript" charset="utf-8"></script>
<script src="https://js.hereapi.cn/v3/3.0/mapsjs-ui.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="https://js.hereapi.cn/v3/3.0/mapsjs-ui.css" />

<script type="text/javascript">
$( document ).ready(function() {

// Instantiate a map and platform object:
var platform = new H.service.Platform({
  'apikey': '5mKawERqnzL1KMnNIt4n42gAV8eLomjQPKf5S5AAcZg'
});

// // Retrieve the target element for the map:
// var targetElement = document.getElementById('mapContainer');
//
// // Get default map types from the platform object:
// var defaultLayers = platform.createDefaultLayers();
//
// // Instantiate the map:
// var map = new H.Map(
//   document.getElementById('mapContainer'),
//   defaultLayers.vector.normal.map,
//   {
//     zoom: 13,
//     center: { lat: -20.213750, lng: -70.152506 }
//   }
// );
//
// @foreach($suspectCases as $key => $case)
//   @if($case->pscr_sars_cov_2 == 'positive')
//     @if($case->patient->demographic != null)
//
//       // Create the parameters for the geocoding request:
//         var geocodingParams = {
//             searchText: '{{$case->patient->demographic->address}}, {{$case->patient->demographic->commune}}, chile'
//           };
//
//       // Define a callback function to process the geocoding response:
//       var onResult = function(result) {
//
//         var locations = result.Response.View[0].Result,
//             position,
//             marker;
//
//         // Add a marker for each location found
//         for (i = 0;  i < locations.length; i++) {
//           position = {
//             lat: locations[i].Location.DisplayPosition.Latitude,
//             lng: locations[i].Location.DisplayPosition.Longitude
//           };
//           marker = new H.map.Marker(position);
//           map.addObject(marker);
//         }
//
//       };
//
//       // Get an instance of the geocoding service:
//       var geocoder = platform.getGeocodingService();
//
//       // Error
//       geocoder.geocode(geocodingParams, onResult, function(e) {
//         alert(e);
//       });
//     @endif
//   @endif
// @endforeach

// Obtain the default map types from the platform
 var maptypes = platform.createDefaultLayers();

 // Instantiate and display a map
 var map = new H.Map(document.getElementById('mapContainer'), maptypes.vector.normal.map, {
   center: {lat: -20, lng: -70},
   zoom: 8
 });

 // Enable the event system on the map instance:
var mapEvents = new H.mapevents.MapEvents(map);

// Instantiate the default behavior, providing the mapEvents object:
new H.mapevents.Behavior(mapEvents);



});
</script>

@endsection
