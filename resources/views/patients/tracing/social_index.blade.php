@extends('layouts.app')

@section('title', 'Seguimiento Social')

@section('content')
<h3 class="mb-3">Seguimiento Social</h3>

<ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
    @foreach($request_types as $request_type)
      @if($request_type->type->name == 'Licencia Medica SEREMI')
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

<div class="tab-content mt-3">
    @foreach($request_types as $request_type)
        <div class="tab-pane" id="{{ str_replace(" ","_",$request_type->type->name) }}" role="tabpanel" >
            <h4>{{ $request_type->type->name }}</h4>
            <br>
            <h5>Solicitudes pendientes de respuesta.</h5>

            <div class="table-responsive">
              <table class="table table-sm table-bordered table-striped small">
                  <thead>
                      <tr class="text-center">
                          <th>N° Solicitud</th>
                          <th>Fecha Solicitud</th>
                          <th>Run o Identificación</th>
                          <th>Paciente</th>
                          <th>Detalle</th>
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
            </div>

            <hr>
            <h5>Solicitudes con respuesta.</h5>
            <div class="table-responsive">
              <table class="table table-sm table-bordered table-striped small">
                  <thead>
                      <tr class="text-center">
                          <th>N° Solicitud</th>
                          <th>Fecha Solicitud</th>
                          <th>Run o Identificación</th>
                          <th>Paciente</th>
                          <th>Detalle</th>
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
            </div>

            {{ $tracing_request_complete->links() }}

        </div>
    @endforeach
</div>


@endsection

@section('custom_js')

<script type="text/javascript">
    $('#myTab a[href="#Licencia Medica APS"]').tab('show') // Select tab by name
</script>

@endsection
