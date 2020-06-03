@extends('layouts.app')
@section('title', 'Georeferenciación')
@section('content')
@include('help_basket.nav')

<h3 class="mb-3"><i class="fas fa-globe-americas"></i> Georreferenciación</h3>

<div style="width: 100%; height: 650px" id="map"></div>

@endsection

@section('custom_js')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-core.js" type="text/javascript" charset="utf-8"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-service.js" type="text/javascript" charset="utf-8"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-ui.js" type="text/javascript" charset="utf-8"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-mapevents.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="https://js.api.here.com/v3/3.1/mapsjs-ui.css" />

<script type="text/javascript">
$( document ).ready(function() {
    

        /**
 * Creates a new marker and adds it to a group
 * @param {H.map.Group} group       The group holding the new marker
 * @param {H.geo.Point} coordinate  The location of the marker
 * @param {String} html             Data associated with the marker
 */
function addMarkerToGroup(group, coordinate, html) {
  var marker = new H.map.Marker(coordinate);
  // add custom data to the marker
  marker.setData(html);
  group.addObject(marker);
}

/**
 * Add two markers showing the position of Liverpool and Manchester City football clubs.
 * Clicking on a marker opens an infobubble which holds HTML content related to the marker.
 * @param  {H.Map} map      A HERE Map instance within the application
 */
function addInfoBubble(map) {
  var group = new H.map.Group();

  map.addObject(group);
  var svgMarkupRed = '<svg width="10" height="10" ' +
        'xmlns="http://www.w3.org/2000/svg">' +
        '<rect stroke="white" fill="red" x="1" y="1" width="22" ' +
        'height="22" /><text x="12" y="18" font-size="12pt" ' +
        'font-family="Arial" font-weight="bold" text-anchor="middle" ' +
        'fill="white"></text></svg>';

  // add 'tap' event listener, that opens info bubble, to the group
  group.addEventListener('tap', function (evt) {
    // event target is the marker itself, group is a parent event target
    // for all objects that it contains
    var bubble =  new H.ui.InfoBubble(evt.target.getGeometry(), {
      // read custom data
      content: evt.target.getData()
    });
    // show info bubble
    ui.addBubble(bubble);
  }, false);
  @foreach($helpbaskets as $helpbasket)
  addMarkerToGroup(group, {lat:{{$helpbasket->latitude}}, lng:{{$helpbasket->longitude}}, svgMarkupRed}  ,
  "<b>{{$helpbasket->fullName}}</b><br/> {{$helpbasket->identifier}}<br/> Entregado el: {{$helpbasket->created_at}}");  
  @endforeach
}

/**
 * Boilerplate map initialization code starts below:
 */

// initialize communication with the platform
// In your own code, replace variable window.apikey with your own apikey
var platform = new H.service.Platform({
    'apikey': '5mKawERqnzL1KMnNIt4n42gAV8eLomjQPKf5S5AAcZg'
});
var defaultLayers = platform.createDefaultLayers();

// initialize a map - this map is centered over Europe
var map = new H.Map(document.getElementById('map'),
  defaultLayers.vector.normal.map,{
  center: {lat: -20.26, lng: -70.13},
  
  zoom: 12.7,
  pixelRatio: window.devicePixelRatio || 1
});
// add a resize listener to make sure that the map occupies the whole container
window.addEventListener('resize', () => map.getViewPort().resize());

// MapEvents enables the event system
// Behavior implements default interactions for pan/zoom (also on mobile touch environments)
var behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

// create default UI with layers provided by the platform
var ui = H.ui.UI.createDefault(map, defaultLayers);

// Now use the map as required...
addInfoBubble(map);

});    
</script>

@endsection