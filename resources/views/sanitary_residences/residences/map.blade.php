@extends('layouts.app')

@section('title', 'Georeferenciación')

@section('content')  
    <div id="map" style="width: 100%; height: 650px"></div>
@endsection

@section('custom_js')

<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script
      src="https://maps.googleapis.com/maps/api/js?key={{env('API_KEY_GOOGLE_MAPS')}}&callback=initMap&libraries=&v=weekly"
      defer
    ></script>

<script>
      (function(exports) {
        "use strict";

        // In this example, we center the map, and add a marker, using a LatLng object
        // literal instead of a google.maps.LatLng object. LatLng object literals are
        // a convenient way to add a LatLng coordinate and, in most cases, can be used
        // in place of a google.maps.LatLng object.

        function initMap() {
          var mapOptions = {
            zoom: 12.7,
            center: {
            lat: {{ env('LATITUD') }},
            lng: {{ env('LONGITUD') }}
            }
          };
          exports.map = new google.maps.Map(
            document.getElementById("map"),
            mapOptions
          );

          @foreach($dataArray as $residencia)
          @if($residencia['latitude'])
          var marker = new google.maps.Marker({
            // The below line is equivalent to writing:
            // position: new google.maps.LatLng(-34.397, 150.644)
            @if($residencia['availableRooms']>0)
            icon: {url: "http://maps.google.com/mapfiles/ms/icons/green-dot.png"},
            @else
            icon: {url: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"},
            @endif
            position: {                
              lat: {{$residencia['latitude'] }},
              lng: {{$residencia['longitude'] }}
            },
            map: exports.map
          }); // You can use a LatLng literal in place of a google.maps.LatLng object when
          // creating the Marker object. Once the Marker object is instantiated, its
          // position will be available as a google.maps.LatLng object. In this case,
          // we retrieve the marker's position using the
          // google.maps.LatLng.getPosition() method.
          
          var content = "<h5><p align='center'><b>{{$residencia['residenceName']}} </h5></b></p><hr> <p> Habitaciones Totales:{{$residencia['totalRooms']}} (Single:{{$residencia['totalsinglebyresidence'] }} Doble:{{$residencia['totaldoublebyresidence'] }}) </p><p> Habitaciones Ocupadas:{{$residencia['occupiedRooms']}} </p> Pacientes en Residencia:{{$residencia['patients']}} <hr> <p>Habitaciones Disponibles: {{$residencia['availableRooms'] }} ( Single: {{$residencia['single'] }} Doble:{{$residencia['double'] }} )</p>";
          var infowindow = new google.maps.InfoWindow();

        //   var infowindow = new google.maps.InfoWindow({
        
        //   });
        //   google.maps.event.addListener(marker, "click", function() {
        //     infowindow.open(exports.map, marker);
        //   });
          google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
        return function() {
        infowindow.setContent(content);
        infowindow.open(map,marker);
    };
})(marker,content,infowindow));



          @endif
          @endforeach
        }
        

        exports.initMap = initMap;
      })((this.window = this.window || {}));
    </script>
@endsection