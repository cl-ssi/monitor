@extends('layouts.app')

@section('title', 'Reporte Minsal')

@section('content')

<div class="card mb-3">
    <div class="card-body">

        <div class="form-row">
            <fieldset class="form-group col-12 col-sm-4 col-md-4">
                <h3 class="mb-3">{{$hl7ResultMessage->getStatusValueAttribute()}}</h3>
            </fieldset>
            <fieldset class="form-group col-12 col-sm-4 col-md-4">
                <h3 class="mb-3"></h3>
            </fieldset>
            <!-- <fieldset class="form-group col-12 col-sm-4 col-md-4">
              <div class="d-flex justify-content-end">
                <a class="btn btn-warning btn" href="{{ route('lab.suspect_cases.Hl7Result_message_dismiss', [$hl7ResultMessage]) }}">
                  Descartar
                </a>
              </div>
            </fieldset> -->
        </div>


        <div class="form-row">
            <fieldset class="form-group col-12 col-sm-4 col-md-4">
                <label for="for_name">ID MSG</label>
                <input type="text" class="form-control" id="for_name" name="name"
                       value="{{$hl7ResultMessage->id}}" style="text-transform: uppercase;"
                       disabled>
            </fieldset>

            <fieldset class="form-group col-12 col-sm-4 col-md-4">
                <label for="for_name">RUT/PASAPORTE</label>
                <input type="text" class="form-control" id="for_patient_identifier" name="patient_identifier"
                       value="{{$hl7ResultMessage->patient_identifier}}" style="text-transform: uppercase;"
                       disabled>
            </fieldset>
        </div>

        <div class="form-row">
            <fieldset class="form-group col-12 col-sm-4 col-md-4">
                <label for="for_name">Nombres</label>
                <input type="text" class="form-control" id="for_name" name="name"
                       value="{{$hl7ResultMessage->patient_names}}" style="text-transform: uppercase;"
                       disabled>
            </fieldset>

            <fieldset class="form-group col-12 col-sm-4 col-md-4">
                <label for="for_fathers_family">Apellido Paterno</label>
                <input type="text" class="form-control" id="for_fathers_family"
                       name="fathers_family" value="{{$hl7ResultMessage->patient_family_father}}"
                       style="text-transform: uppercase;"
                       disabled>
            </fieldset>

            <fieldset class="form-group col-12 col-sm-4 col-md-4">
                <label for="for_mothers_family">Apellido Materno</label>
                <input type="text" class="form-control" id="for_mothers_family"
                       name="mothers_family" value="{{$hl7ResultMessage->patient_family_mother}}"
                       style="text-transform: uppercase;" disabled>
            </fieldset>

        </div>

        <div class="form-row">
            <fieldset class="form-group col-12 col-sm-4 col-md-4">
                <label for="for_name">Fecha Muestra</label>
                <input type="text" class="form-control" id="for_name" name="name"
                       value="{{$hl7ResultMessage->sample_observation_datetime}}" style="text-transform: uppercase;"
                       disabled>
            </fieldset>

            <fieldset class="form-group col-12 col-sm-4 col-md-4">
                <label for="for_name">Fecha Resultado</label>
                <input type="text" class="form-control" id="for_name" name="name"
                       value="{{$hl7ResultMessage->observation_datetime}}" style="text-transform: uppercase;"
                       disabled>
            </fieldset>

            <fieldset class="form-group col-12 col-sm-4 col-md-4">
                <label for="for_fathers_family">Resultado</label>
                <input type="text" class="form-control" id="for_fathers_family"
                       name="fathers_family" value="{{$hl7ResultMessage->observation_value}}"
                       style="text-transform: uppercase;"
                       disabled>
            </fieldset>

        </div>

      </div>
</div>

<hr>

@if($hl7ResultMessage->hl7ErrorMessage)
  <div class="alert alert-danger" role="alert">
    {{$hl7ResultMessage->hl7ErrorMessage->error . ": " . $hl7ResultMessage->hl7ErrorMessage->error_message}}
  </div>
@endif

<hr>

<!-- muestras asociadas -->
@if($hl7ResultMessage->status == "too_many_cases" || $hl7ResultMessage->status == "assigned_to_case")

  <h4 class="mb-3">Muestras disponibles</h3>

  <table class="table table-sm table-bordered table-striped small">
  	<thead>
  		<tr class="text-center">
        <th>ID</th>
        <th>F.TOMA DE MUESTRA</th>
        <!-- <th>F.RECEPCIÓN MUESTRA</th> -->
        <th>F.RESULTADO</th>
        <th>RESULTADO</th>
        <th>OBSERVACIÓN</th>
        <th>ACCIÓN</th>
  		</tr>
  	</thead>
  	<tbody>
      @foreach($hl7ResultMessage->suspectCases as $suspectCase)
        <tr>
          <!-- <td>{{$suspectCase->id}}</td> -->
          <td>
              @can('SuspectCase: edit')
              <a href="{{ route('lab.suspect_cases.edit', $suspectCase )}}">
              @endcan
                  {{ $suspectCase->id }}
              @can('SuspectCase: edit')
              </a>
              @endcan
          </td>
          <td>{{$suspectCase->sample_at}}</td>
          <!-- <td>{{$suspectCase->reception_at}}</td> -->
          <td>{{$suspectCase->pcr_sars_cov_2_at}}</td>
          <td>{{$suspectCase->pcr_sars_cov_2}}</td>
          <td>{{$suspectCase->observation}}</td>
          <td class="text-center">
              @if($hl7ResultMessage->status == "too_many_cases")
                <a class="btn btn-primary btn-sm"  href="{{ route('lab.suspect_cases.hl7Result_message_suspectCase_asignation', [$hl7ResultMessage,$suspectCase]) }}">
                  Asignar
                </a>
              @endif
          </td>
        </tr>
      @endforeach
  	</tbody>
  </table>

@else

  <h4 class="mb-3">Búsqueda de muestras</h3>

  <div>
    <div class="col-12 col-sm-12 col-md-6">
      <form method="get" action="{{ route('lab.suspect_cases.reports.integration_hetg_monitor_pendings_details',$hl7ResultMessage) }}">
      <div class="row">
        <div class="col-12 mb-3">
            <input type="checkbox" name="positivos" id="chk_positivos" v="Positivos" {{ ($request->positivos)?'checked':'' }} /> Positivos
            <input type="checkbox" name="negativos" id="chk_negativos" v="Negativos" {{ ($request->negativos)?'checked':'' }} /> Negativos
            <input type="checkbox" name="pendientes" id="chk_pendientes" v="Pendientes" {{ ($request->pendientes)?'checked':'' }} /> Pendientes
            <input type="checkbox" name="rechazados" id="chk_rechazados" v="Rechazados" {{ ($request->rechazados)?'checked':'' }} /> Rechazados
            <input type="checkbox" name="indeterminados" id="chk_indeterminados" v="Indeterminados" {{ ($request->indeterminados)?'checked':'' }} /> Indeterminados
        </div>
      </div>
      <div class="row">
        <div class="col-12">
            <div class="input-group mb-3">
              <div class="input-group-prepend"><span class="input-group-text">Búsqueda</span></div>
              <input class="form-control" type="text" name="text" value="{{$request->text}}" placeholder="Rut / Nombre">
              <div class="input-group-append"><button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button></div>
            </div>
          </div>
      </div>
      </form>
    </div>
  </div>

  @if($suspectCases)
  <div class="table-responsive">
  <table class="table table-sm table-bordered" id="tabla_casos">
      <thead>
          <tr>
              <th nowrap>° Monitor</th>
              <th>Fecha muestra</th>
              <th>Origen</th>
              <th>Nombre</th>
              <th>RUN</th>
              <th>Edad</th>
              <th>Sexo</th>
              <th class="alert-danger">PCR SARS-Cov2</th>
              <!-- <th>Resultado IFD</th>
              <th>Ext. Lab</th>
              <th>Epivigila</th> -->
              <th>Fecha de Resultado</th>
              <th>Estado</th>
              <th>Observación</th>
              <th>Acción</th>
          </tr>
      </thead>
      <tbody id="tableCases">
          @foreach($suspectCases as $suspectCase)
          <tr class="row_{{$suspectCase->covid19}} {{ ($suspectCase->pcr_sars_cov_2 == 'positive')?'table-danger':''}}">
              <td class="text-center">
                  {{ $suspectCase->id }}<br>
                  <small>{{ $suspectCase->laboratory->alias }}</small>
                  @canany(['SuspectCase: edit','SuspectCase: tecnologo'])
                  <a href="{{ route('lab.suspect_cases.edit', $suspectCase) }}" class="btn_edit"><i class="fas fa-edit"></i></a>
                  @endcan
                  <small>{{ $suspectCase->minsal_ws_id }}</small>
              </td>
              <td nowrap class="small">{{ (isset($suspectCase->sample_at))? $suspectCase->sample_at->format('Y-m-d'):'' }}</td>
              <td>
                  {{ ($suspectCase->establishment) ? $suspectCase->establishment->alias . ' - ': '' }}
                  {{ $suspectCase->origin }}
              </td>
              <td>
                  @if($suspectCase->patient)
                  <a class="link" href="{{ route('patients.edit', $suspectCase->patient) }}">
                      {{ $suspectCase->patient->fullName }}
                      @if($suspectCase->gestation == "1") <img align="center" src="{{ asset('images/pregnant.png') }}" width="24"> @endif
                      @if($suspectCase->close_contact == "1") <img align="center" src="{{ asset('images/contact.png') }}" width="24"> @endif
                   </a>
                   @endif
              </td>
              <td class="text-center" nowrap>
                  @if($suspectCase->patient)
                  {{ $suspectCase->patient->identifier }}
                  @endif
              </td>
              <td>{{ $suspectCase->age }}</td>
              <td>{{ strtoupper($suspectCase->gender[0]) }}</td>
              <td>{{ $suspectCase->covid19 }}
                  @if($suspectCase->file)
                      <a href="{{ route('lab.suspect_cases.download', $suspectCase->id) }}"
                      target="_blank"><i class="fas fa-paperclip"></i>&nbsp
                  </a>
                  @endif

                  @if ($suspectCase->laboratory->pdf_generate == 1 && $suspectCase->pcr_sars_cov_2 <> 'pending')
                  <a href="{{ route('lab.print', $case) }}"
                      target="_blank"><i class="fas fa-paperclip"></i>&nbsp
                  </a>
                  @endif
                  @if($suspectCase->positive_condition == "Excreción Viral Remanente") <img align="center" src="{{ asset('images/head-side-virus-solid.png') }}" width="24"> @endif
                  @if($suspectCase->positive_condition == "Reinfección") <img align="center" src="{{ asset('images/viruses-solid.png') }}" width="24"> @endif

              </td>
              <!-- <td class="{{ ($suspectCase->result_ifd <> 'Negativo' AND $suspectCase->result_ifd <> 'No solicitado')?'text-danger':''}}">{{ $suspectCase->result_ifd }} {{ $suspectCase->subtype }}</td>
              <td>{{ $suspectCase->external_laboratory }}</td>
              <td>{{ $suspectCase->epivigila }}</td> -->
              <td>{{ ($suspectCase->pcr_sars_cov_2_at)?$suspectCase->pcr_sars_cov_2_at->format('d-m-Y'):'' }}</td>
              <td>{{ $suspectCase->patient->status }}</td>
              <td class="text-muted small">{{ $suspectCase->observation }}</td>
              <td class="text-center">
                  <a class="btn btn-primary btn-sm" href="{{ route('lab.suspect_cases.hl7Result_message_suspectCase_asignation', [$hl7ResultMessage,$suspectCase]) }}">
                    Asignar
                  </a>
              </td>
          </tr>
          @endforeach
      </tbody>
  </table>
  </div>

  {{ $suspectCases->appends(request()->query())->links() }}
  @endif

@endif

@endsection

@section('custom_js')

@endsection
