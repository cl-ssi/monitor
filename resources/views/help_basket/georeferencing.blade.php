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
    // Inicialiar comunicación 
    var platform = new H.service.Platform({
        'apikey': '5mKawERqnzL1KMnNIt4n42gAV8eLomjQPKf5S5AAcZg'
    });

    var defaultLayers = platform.createDefaultLayers();

    //Step 2: initialize a map - this map is centered over Europe
    var map = new H.Map(document.getElementById('map'),
        defaultLayers.vector.normal.map, {
            center: {
                lat: {{ env('LATITUD') }},
                lng: {{ env('LONGITUD') }}
            },
            zoom: 12.7,
            pixelRatio: window.devicePixelRatio || 1
        });
    // add a resize listener to make sure that the map occupies the whole container
    window.addEventListener('resize', () => map.getViewPort().resize());

    //Step 3: make the map interactive
    // MapEvents enables the event system
    // Behavior implements default interactions for pan/zoom (also on mobile touch environments)
    var behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

    // Create the default UI components
    var ui = H.ui.UI.createDefault(map, defaultLayers);




    // Create a marker using the previously instantiated icon:
    //var marker = new H.map.Marker({ lat: '-20.22123000', lng: '-70.15085000' }, { icon: iconBlue });



    var svgMarkupGreen = '<svg width="10" height="10" ' +
        'xmlns="http://www.w3.org/2000/svg">' +
        '<rect stroke="white" fill="green" x="1" y="1" width="22" ' +
        'height="22" /><text x="12" y="18" font-size="12pt" ' +
        'font-family="Arial" font-weight="bold" text-anchor="middle" ' +
        'fill="white"></text></svg>';
    var iconGreen = new H.map.Icon(svgMarkupGreen);

    @foreach($helpbaskets as $helpbasket)
    var latitude;
    var longitude;
    latitude ='{{$helpbasket->latitude}}';
    longitude ='{{$helpbasket->longitude}}';
    var marker = new H.map.Marker({ lat: latitude, lng: longitude}, { icon: iconGreen }  );
    
    map.addObject(marker);
    
    var content = "";
    
    content = content + "<b>{{$helpbasket->fullName}}</b><br/> {{$helpbasket->identifier}}<br/> Entregado el: {{$helpbasket->created_at}}";

    var bubble;
    marker.addEventListener('pointerenter', function(evt) {
        bubble = new H.ui.InfoBubble({
            lat: latitude,
            lng: longitude
        }, {
            content: content
        });
        ui.addBubble(bubble);
    }, false);
    marker.addEventListener('pointerleave', function(evt) {
        bubble.close();
    }, false);

    @endforeach
</script>

@endsection