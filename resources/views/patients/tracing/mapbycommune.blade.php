@extends('layouts.app')

@section('title', 'Georeferenciación')

@section('content')  
<h3 class="mb-3"><i class="fas fa-globe-americas"></i> Seguimiento en Mis Comunas</h3> <small>(Rojo:Caso Indice - Azul:Contacto Alto Riesgo)</small>
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

          @foreach($patients as $patient)
          @if($patient->demographic->latitude)
          var marker = new google.maps.Marker({
            // The below line is equivalent to writing:
            // position: new google.maps.LatLng(-34.397, 150.644)
            @if($patient->tracing->index)
            icon: {url: "http://maps.gstatic.com/mapfiles/ridefinder-images/mm_20_red.png"},
            @else
            icon: {url: "http://maps.gstatic.com/mapfiles/ridefinder-images/mm_20_blue.png"},
            @endif
            position: {                
              lat: {{$patient->demographic->latitude}},
              lng: {{$patient->demographic->longitude }}
            },
            map: exports.map
          }); // You can use a LatLng literal in place of a google.maps.LatLng object when
          // creating the Marker object. Once the Marker object is instantiated, its
          // position will be available as a google.maps.LatLng object. In this case,
          // we retrieve the marker's position using the
          // google.maps.LatLng.getPosition() method.
          
          
          var content = "<h6><p align='center'><b><a href='{{route('patients.edit',$patient)}}' target='_blank'>{{$patient->fullName}}</a></b></p></h6><hr> <p>Inicio de Cuaretena:{{ $patient->tracing->quarantine_start_at->format('Y-m-d') }} </p> <p> Fin de Cuarentena:{{ $patient->tracing->quarantine_end_at->format('Y-m-d') }} ({{ $patient->tracing->quarantine_start_at->diffInDays(Carbon\Carbon::now()) }}) </p> <hr> <p>Notificación:{{ ($patient->tracing->notification_at)? $patient->tracing->notification_at->format('Y-m-d') : '' }}</p> <p>Último evento:{{ ($patient->tracing->events->last()) ? $patient->tracing->events->last()->event_at->format('Y-m-d') : '' }}</p> <p>Funcionario:{{ ($patient->tracing->events->last()) ? $patient->tracing->events->last()->user->name : '' }}</p>";
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