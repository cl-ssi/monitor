@extends('layouts.app')

@section('title', 'Seguimiento de Casos')

@section('content')

<h3 class="">Seguimiento de Casos Positivos y Contactos.</h3>

<!--a class="btn btn-outline-success btn-sm mb-3" id="downloadLink" onclick="exportF(this)">Descargar en excel</a-->
<a class="btn btn-outline-success btn-sm mb-3" id="downloadLink" href="" >Descargar en excel</a>

<form method="get" class="form-inline mb-3" action="">

  <!-- <a type="button" class="btn btn-success btn-sm mb-3" href="">Descargar <i class="far fa-file-excel"></i></a> -->
  <div class="form-group">
      <label for="for_to">fecha:</label>
      <input type="datetime-local" class="form-control mx-sm-3" id="for_to" name="to" value="">
  </div>
  <div class="form-group">
      <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
  </div>

</form>

</main>

<main>

<div class="table-responsive">
    <table class="table table-sm table-bordered table-responsive small" id="tabla_casos">
        <thead>
            <tr class="text-center">
                <th rowspan="2">°</th>
                <th colspan="3">Caso Indice</th>
                <th colspan="39">Contactos</th>
            </tr>
            <tr class="text-center">
                <th>N°</th>
                <th>RUN o IDENTIFICADOR</th>
                <th nowrap>NOMBRE UNIDO</th>
                <th>NOMBRES</th>
                <th>APELLIDO PATERNO</th>
                <th>APELLIDO MATERNO</th>
                <th>NOMBRE UNIDO
                <th>FECHA DE NACIMIENTO</th>
                <th>TELEFONO</th>
                <th>TELEFONO</th>
                <th>VÍA</th>
                <th>2</th>
                <th>NÚMERO DE RESIDENCIA</th>
                <th>NÚMERO  DEPARTAMENTO O NÚMERO DE CASA</th>
                <th>OTRA INFORMACIÓN BLOCK CONDOMINIO TORRE NOMBRE TOMA</th>
                <th>CORREO ELECTRONICO</th>
                <th>COMUNA RESIDENCIA</th>
                <th>REGIÓN (EVENTUALMENTE PUEDE SER DE OTRA REGIÓN)</th>
                <th>FECHA DE ÚLTIMO CONTACTO CON EL CASO</th>
                <th>FECHA FIN SEGUIMIENTO</th>
                <th>SEGUIMIENTO FINALIZADO</th>
                <th>CATEGORÍA (MIRAR DICCIONARIO)</th>
                <th>ESPECIFIQUE</th>
                <th>FECHA DE NOTIFICACIÓN AL CONTACTO ESTRECHO</th>
                <th>OBSERVACIÓN</th>
                <th>PRESENTACIÓN DE SÍNTOMAS</th>
                <th>DETALLES SINTOMAS</th>
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
                  @foreach($patient->contactPatient as $contact)
                  <tr>
                    <td>°</td>
                    <td>{{ $contact->self_patient->tracing->id }}</td>
                    <td>{{ $patient->identifier }}</td>
                    <td>{{ $patient->fullName }}</td>
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
                    <td>{{ ($contact->patient->tracing)? $contact->patient->tracing->quarantine_end_at->format('d-m-Y') : '' }}</td>
                    <td>{{ ($contact->patient->tracing)? $contact->patient->tracing->StatusDesc : '' }}</td>
                    <td>{{ $contact->CategoryDesc  }}</td>
                    <td>{{ $contact->RelationshipName }}</td>
                    <td>{{ ($contact->patient->tracing)? $contact->patient->tracing->notification_at->format('d-m-Y') : '' }}</td>
                    <td>{{ ($contact->patient->tracing)? $contact->patient->tracing->observations : '' }}</td>
                    <td>{{ ($contact->patient->tracing)? $contact->patient->tracing->SymptomsDesc : '' }}</td>
                    <td>{{ $contact->patient->tracing->events->last()->symptoms }}</td>
                    <td></td>
                    <td>{{ ($contact->patient->tracing)? $contact->patient->tracing->chronic_diseases : '' }}</td>
                    @foreach($contact->patient->suspectCases as $suspectCases)
                      @if($suspectCases->pscr_sars_cov_2 == 'positive')
                          <td> SI </td>
                        @else
                          <td> NO </td>
                        @endif
                    @endforeach
                    <td>{{ ($contact->patient->tracing)? $contact->patient->tracing->RequiresLicenceDesc : '' }}</td>
                    <td>{{ ($contact->patient->tracing && $contact->patient->tracing->cannot_quarantine)? 'NO' : 'SI' }}</td>
                    <td>{{ ($contact->patient->tracing)? $contact->patient->tracing->cannot_quarantine : '' }}</td>
                    <td>{{ $contact->patient->tracing->events->last()->type->name }}</td>
                    <td>{{ ($contact->patient->tracing)? $contact->patient->tracing->prevision : '' }}</td>
                  @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

</main>


@endsection

@section('custom_js_head')


@endsection
