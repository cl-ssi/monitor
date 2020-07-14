@extends('layouts.app')

@section('title', 'Seguimiento de Casos')

@section('content')

<h3 class="">Seguimiento de Casos Positivos y Contactos.</h3>

@if($request->date_from && $request->date_to)
    <a class="btn btn-outline-success btn-sm mb-3" type="button" id="downloadLink" onclick="exportF(this)"><i class="far fa-file-excel"></i> Descargar en excel</a>
@endif

<form method="get" class="form-inline mb-3" action="{{ route('lab.suspect_cases.reports.tracing_minsal') }}">

  <div class="form-group">
      <label for="for_date_from">Desde:</label>
      <input type="date" class="form-control mx-sm-3" id="for_date_from" name="date_from" value="{{ $request->date_from }}">
  </div>

  <div class="form-group">
      <label for="for_date_to">Hasta:</label>
      <input type="date" class="form-control mx-sm-3" id="for_date_to" name="date_to" value="{{ $request->date_to }}">
  </div>

  <div class="form-group">
      <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
  </div>

</form>

</main>

<main>

<div class="table-responsive">
    <table class="table table-sm table-bordered table-striped small" id="tabla_files" >
        <thead>
            <tr class="text-center">
                <th rowspan="2">°</th>
                <th colspan="4">Caso Indice</th>
                <th colspan="33">Contactos</th>
            </tr>
            <tr class="text-center">
                <th>N°</th>
                <th>FECHA NOTIFICACIÓN</th>
                <th>RUN o IDENTIFICADOR</th>
                <th nowrap>NOMBRE UNIDO</th>
                <th>RUN o IDENTIFICADOR</th>
                <th>NOMBRES</th>
                <th>APELLIDO PATERNO</th>
                <th>APELLIDO MATERNO</th>
                <th nowrap>NOMBRE UNIDO</th>
                <th>FECHA DE NACIMIENTO</th>
                <th>TELEFONO</th>
                <th>TELEFONO</th>
                <th>VÍA</th>
                <th>NOMBRE</th>
                <th>NÚMERO DE RESIDENCIA</th>
                <th>NÚMERO  DEPARTAMENTO O NÚMERO DE CASA</th>
                <th>OTRA INFORMACIÓN BLOCK CONDOMINIO TORRE NOMBRE TOMA</th>
                <th>CORREO ELECTRONICO</th>
                <th>COMUNA RESIDENCIA</th>
                <th>REGIÓN</th>
                <th>FECHA DE ÚLTIMO CONTACTO CON EL CASO</th>
                <th>FECHA FIN SEGUIMIENTO</th>
                <th>SEGUIMIENTO FINALIZADO</th>
                <th>CATEGORÍA</th>
                <th>ESPECIFIQUE</th>
                <th>FECHA DE NOTIFICACIÓN AL CONTACTO ESTRECHO</th>
                <th>OBSERVACIÓN</th>
                <th>PRESENTACIÓN DE SÍNTOMAS</th>
                <th>FIEBRE</th>
                <th>TOS</th>
                <th>DIFICULTAD RESPIRATORIA</th>
                <th>DOLOR MUSCULAR</th>
                <th>DOLOR DE GARGANTA</th>
                <th>DOLOR DE CABEZA</th>
                <th>DIARREA</th>
{{--                <th>DETALLES SINTOMAS</th>--}}
                <th>OTRO, ¿CUÁL?</th>
                <th>ENFERMEDAD CRÓNICA</th>
                <th>COVID+</th>
                <th>LICENCIA MÉDICA</th>
                <th>CUARENTENA</th>
                <th>CUARENTENA ¿POR QUÉ NO?</th>
                <th>ACCIONES</th>
                <th>AFILIACIÓN</th>
            </tr>
        </thead>
        <tbody>
        @foreach($patients as $patient)

            @if($patient->contactPatient->count() > 0)
                @foreach($patient->contactPatient as $contact)

                    <tr>
                        <td>°</td>
                        <td>{{ $patient->tracing->id }}</td>
                        @if($patient->tracing->notification_at != null)
                            <td>{{ $patient->tracing->notification_at->format('d-m-Y') }}</td>
                        @else
                            <td></td>
                        @endif
                        <td>{{ $patient->identifier }}</td>
                        <td>{{ $patient->fullName }}</td>

                        @if($contact->patient)
                            <td>{{ $contact->patient->identifier }}</td>
                            <td>{{ $contact->patient->name }}</td>
                            <td>{{ $contact->patient->fathers_family }}</td>
                            <td>{{ $contact->patient->mothers_family }}</td>
                            <td>{{ $contact->patient->fullName }}</td>
                            <td>{{ $contact->patient->birthday->format('d-m-Y') }}</td>
                            <td>{{ $contact->patient->demographic->telephone }}</td>
                            <td>{{ $contact->patient->demographic->telephone2 }}</td>
                            <td>{{ $contact->patient->demographic->street_type }}</td>
                            <td>{{ $contact->patient->demographic->address }}</td>
                            <td>{{ $contact->patient->demographic->number }}</td>
                            <td>{{ $contact->patient->demographic->department }}</td>
                            <td>{{ $contact->patient->demographic->suburb }}</td>
                            <td>{{ $contact->patient->demographic->email }}</td>
                            <td>{{ $contact->patient->demographic->commune->name }}</td>
                            <td>{{ $contact->patient->demographic->region->name }}</td>
                            <td>{{ $contact->last_contact_at }}</td>

                            @if($contact->patient->tracing)
                                <td>{{ ($contact->patient->tracing->quarantine_end_at)? $contact->patient->tracing->quarantine_end_at->format('d-m-Y') : '' }}</td>
                                <td>{{ ($contact->patient->tracing->StatusDesc)? $contact->patient->tracing->StatusDesc : '' }}</td>
                            @else
                                <td></td>
                                <td></td>
                            @endif

                            <td>{{ $contact->CategoryDesc  }}</td>
                            <td>{{ $contact->RelationshipName }}</td>

                            @if($contact->patient->tracing)
                                <td>{{ ($contact->patient->tracing->notification_at)? $contact->patient->tracing->notification_at->format('d-m-Y') : '' }}</td>
                                <td>{{ ($contact->patient->tracing->observations)? $contact->patient->tracing->observations : '' }}</td>
                                <td>{{ ($contact->patient->tracing->symptoms)? $contact->patient->tracing->SymptomsDesc : '' }}</td>
                            @else
                                <td></td>
                                <td></td>
                                <td></td>
                            @endif

                        <!-- DETALLES DE SINTOMAS -->

                            @if($contact->patient->tracing && $contact->patient->tracing->events)

                            @php
                                $symptomsArray =  $contact->patient->tracing->getSymptoms();
                            @endphp

                            <td> {{$symptomsArray['Fiebre'] ? 'Si': 'No'}} </td>
                            <td>{{$symptomsArray['Tos'] ? 'Si': 'No'}}</td>
                            <td>{{$symptomsArray['Dificultad para respirar'] ? 'Si': 'No'}}</td>
                            <td>{{$symptomsArray['Mialgias'] ? 'Si': 'No'}}</td>
                            <td>{{$symptomsArray['Odinofagia'] ? 'Si': 'No'}}</td>
                            <td>{{$symptomsArray['Cefalea'] ? 'Si': 'No'}}</td>
                            <td>{{$symptomsArray['Diarrea'] ? 'Si': 'No'}}</td>
                            <td>{{implode(', ', $symptomsArray[0])}}</td>

{{--                                <td>{{ ($contact->patient->tracing->events->where('symptoms')->last()) ? $contact->patient->tracing->events->where('symptoms')->last()->symptoms : '' }}</td>--}}
                            @else
                                <td></td>
                            @endif

{{--                            <td></td> <!-- OTRO CUAL -->--}}
                            <td>{{ ($contact->patient->tracing)? $contact->patient->tracing->chronic_diseases : '' }}</td>

                            <td>
                                @foreach($contact->patient->suspectCases->where('pscr_sars_cov_2', 'positive') as $suspectCase)
                                    @if($suspectCase->pscr_sars_cov_2 == 'positive')
                                        SI
                                    @endif
                                @endforeach
                            </td>

                            @if($contact->patient->tracing)
                                <td>{{ ($contact->patient->tracing->requires_licence)? $contact->patient->tracing->RequiresLicenceDesc : $contact->patient->tracing->hasAcceptedLicence }}</td>
                                <td>{{ ($contact->patient->tracing->cannot_quarantine)? 'NO' : 'SI' }}</td>
                                <td>{{ ($contact->patient->tracing)? $contact->patient->tracing->cannot_quarantine : '' }}</td>
                            @else
                                <td></td>
                                <td></td>
                                <td></td>
                            @endif

                            @if($contact->patient->tracing && $contact->patient->tracing->events)
                                <td>{{ ($contact->patient->tracing->events->last()) ? $contact->patient->tracing->events->last()->type->name : '' }}</td>
                            @else
                                <td></td>
                            @endif

                            @if($contact->patient->tracing)
                                <td>{{ ($contact->patient->tracing)? $contact->patient->tracing->prevision : '' }}</td>
                            @else
                                <td></td>
                    </tr>
                    @endif
                    @endif

                @endforeach
            @else
                <tr>
                    <td>°</td>
                    <td>{{ $patient->tracing->id }}</td>
                    @if($patient->tracing->notification_at != null)
                        <td>{{ $patient->tracing->notification_at->format('d-m-Y') }}</td>
                    @else
                        <td></td>
                    @endif
                    <td>{{ $patient->identifier }}</td>
                    <td>{{ $patient->fullName }}</td>
                </tr>

            @endif

        @endforeach
        </tbody>
        </table>
    </div>

</main>


@endsection

@section('custom_js')

<script type="text/javascript">
    function exportF(elem) {
        var table = document.getElementById("tabla_files");
        var html = table.outerHTML;
        var html_no_links = html.replace(/<a[^>]*>|<\/a>/g, "");//remove if u want links in your table
        var url = 'data:application/vnd.ms-excel,' + escape(html_no_links); // Set your html table into url
        elem.setAttribute("href", url);
        elem.setAttribute("download", "seguimiento.xls"); // Choose the file name
        return false;
    }
</script>

@endsection

@section('custom_js_head')

@endsection
