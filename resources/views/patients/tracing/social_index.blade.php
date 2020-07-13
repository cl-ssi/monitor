@extends('layouts.app')

@section('title', 'Seguimiento Social')

@section('content')
<h3 class="mb-3">Seguimiento Social</h3>

<ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
    @foreach($request_types as $request_type)
      @if($request_type->type->name == 'Licencia Medica SEREMI' or $request_type->type->name == 'Residencia Sanitaria')
        @can('SocialTracing: seremi')
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" role="tab" aria-selected="true"
              href="#{{ str_replace(" ","_",$request_type->type->name) }}">
                <i class="far fa-file-alt"></i> {{ $request_type->type->name }}
            </a>
        </li>
        @endcan
      @elseif($request_type->type->name == 'Licencia Medica APS')
        @can('SocialTracing: aps')
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" role="tab" aria-selected="true"
              href="#{{ str_replace(" ","_",$request_type->type->name) }}">
                <i class="far fa-file-alt"></i> {{ $request_type->type->name }}
            </a>
        </li>
        @endcan
      @else
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" role="tab" aria-selected="true"
              href="#{{ str_replace(" ","_",$request_type->type->name) }}">
                <i class="far fa-file-alt"></i> {{ $request_type->type->name }}
            </a>
        </li>
      @endif
    @endforeach
</ul>

</main>

<div class="table-responsive">
<div class="row">
  <div class="col-sm">
      <div class="tab-content mt-3">
          @foreach($request_types as $request_type)
              <div class="tab-pane" id="{{ str_replace(" ","_",$request_type->type->name) }}" role="tabpanel" >
                  <h4>{{ $request_type->type->name }}</h4>
                  <br>
                  <h5>Solicitudes pendientes de respuesta.</h5>

                  <!-- <div class="table-responsive"> -->
                    <table class="table table-sm table-bordered table-striped small">
                        <thead>
                            <tr class="text-center">
                                <th>N°</th>
                                <th>Fecha Solicitud</th>
                                <th>Run o Identificación</th>
                                <th>Paciente</th>
                                <th>Detalle</th>
                                <th>Comuna Residencia</th>
                                <th>Estab. Seguimiento</th>
                                <th>Dirección</th>
                                <th>Teléfono</th>
                                <th>Funcionario Solicitud</th>
                                <th>Fecha Respuesta</th>
                                <th>Funcionario Respuesta</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tracing_request_pending as $request)
                              @if($request->request_type_id == $request_type->request_type_id)
                                <tr class="text-right">
                                  <td>{{ $request->id }}</td>
                                  <td>{{ $request->request_at->format('d-m-Y H:i:s') }}</td>
                                  <td>{{ $request->tracing->patient->identifier }}</td>
                                  <td>{{ $request->tracing->patient->fullName }}</td>
                                  <td>{{ $request->details }}</td>
                                  <td>{{ ($request->tracing->patient->demographic) ? $request->tracing->patient->demographic->commune->name : '' }}</td>
                                  <td>{{ $request->tracing->establishment->name }}</td>
                                  <td>{{ ($request->tracing->patient->demographic)? $request->tracing->patient->demographic->FullAddress : '' }}</td>
                                  <td>{{ ($request->tracing->patient->demographic)? $request->tracing->patient->demographic->FullTelephones : '' }}</td>
                                  <td class="text-uppercase">{{ $request->user->name }}</td>
                                  <td>{{ ($request->request_complete_at) ? $request->request_complete_at->format('d-m-Y H:i:s') : '' }}</td>
                                  <td class="text-uppercase">{{ ($request->user_complete_request_id) ? $request->request_complete_user->name : '' }}</td>
                                  <td>
                                  @if($request->request_complete_at)
                                    @if($request->rejection == 1)
                                        Solicitud Rechazada
                                    @else
                                        Solicitud Aceptada
                                    @endif
                                  @else
                                    <a class="btn btn-secondary btn-sm" href="{{ route('patients.tracings.requests.request_complete', $request) }}">
                                      <i class="far fa-edit"></i></a></td>
                                  @endif
                                </tr>
                              @endif
                            @endforeach
                        </tbody>
                    </table>
                  <!-- </div> -->

                  <div id="map_{{ str_replace(" ","_",$request_type->type->name) }}" style="width: 50%; height: 650px; margin-left:auto; margin-right:auto" ></div>

                  <hr>
                  <h5>Solicitudes con respuesta.</h5>
                  <!-- <div class="table-responsive"> -->
                    <table class="table table-sm table-bordered table-striped small">
                        <thead>
                            <tr class="text-center">
                                <th>N°</th>
                                <th>Fecha Solicitud</th>
                                <th>Run o Identificación</th>
                                <th>Paciente</th>
                                <th>Detalle</th>
                                <th>Comuna Residencia</th>
                                <th>Estab. Seguimiento</th>
                                <th>Dirección</th>
                                <th>Teléfono</th>
                                <th>Funcionario Solicitud</th>
                                <th>Fecha Respuesta</th>
                                <th>Funcionario Respuesta</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tracing_request_complete as $request)
                              @if($request->request_type_id == $request_type->request_type_id)
                                <tr class="text-right">
                                  <td>{{ $request->id }}</td>
                                  <td>{{ $request->request_at->format('d-m-Y H:i:s') }}</td>
                                  <td>{{ $request->tracing->patient->identifier }}</td>
                                  <td>{{ $request->tracing->patient->fullName }}</td>
                                  <td>{{ $request->details }}</td>
                                  <td>{{ ($request->tracing->patient->demographic) ? $request->tracing->patient->demographic->commune->name : '' }}</td>
                                  <td>{{ $request->tracing->establishment->name }}</td>
                                  <td>{{ ($request->tracing->patient->demographic)? $request->tracing->patient->demographic->FullAddress : '' }}</td>
                                  <td>{{ ($request->tracing->patient->demographic)? $request->tracing->patient->demographic->FullTelephones : '' }}</td>
                                  <td class="text-uppercase">{{ $request->user->name }}</td>
                                  <td>{{ ($request->request_complete_at) ? $request->request_complete_at->format('d-m-Y H:i:s') : '' }}</td>
                                  <td class="text-uppercase">{{ ($request->user_complete_request_id) ? $request->request_complete_user->name : '' }}</td>
                                  <td>
                                  @if($request->request_complete_at)
                                    @if($request->rejection == 1)
                                        Solicitud Rechazada
                                    @else
                                        Solicitud Aceptada
                                    @endif
                                  @else
                                    <a class="btn btn-secondary btn-sm" href="{{ route('patients.tracings.requests.request_complete', $request) }}">
                                      <i class="far fa-edit"></i></a></td>
                                  @endif
                                </tr>
                              @endif
                            @endforeach
                        </tbody>
                    </table>
                  <!-- </div> -->

                  {{ $tracing_request_complete->links() }}

              </div>
          @endforeach
      </div>
  </div>
</div>
</div>

@endsection

@section('custom_js')

<script type="text/javascript">
    $('#myTab a[href="#Canasta"]').tab('show') // Select tab by name
</script>

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
          @foreach($request_types as $request_type) 
          exports.map = new google.maps.Map(
            document.getElementById("map_{{ str_replace(" ","_",$request_type->type->name) }}"),
            mapOptions
          );

          @foreach($tracing_request_pending as $request)
          @if($request->request_type_id == $request_type->request_type_id)
          @if($request->tracing->patient->demographic->latitude)
          var marker = new google.maps.Marker({
            // The below line is equivalent to writing:
            // position: new google.maps.LatLng(-34.397, 150.644)
            
            position: {                
              lat: {{$request->tracing->patient->demographic->latitude}},
              lng: {{$request->tracing->patient->demographic->longitude }}
            },
            map: exports.map
          }); // You can use a LatLng literal in place of a google.maps.LatLng object when
          // creating the Marker object. Once the Marker object is instantiated, its
          // position will be available as a google.maps.LatLng object. In this case,
          // we retrieve the marker's position using the
          // google.maps.LatLng.getPosition() method.          
          
          
          var content = "<h6><p align='center'><b>{{ $request->tracing->patient->fullName }} </b></p></h6><hr> <p>Dirección:{{ ($request->tracing->patient->demographic)? $request->tracing->patient->demographic->FullAddress : '' }} </p> <p>Teléfono:{{ ($request->tracing->patient->demographic)? $request->tracing->patient->demographic->FullTelephones : '' }}</p>";
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
          @endif
          @endforeach
          @endforeach
        }
        

        exports.initMap = initMap;
      })((this.window = this.window || {}));
    </script>
  

@endsection
