@extends('layouts.app')

@section('title', 'Seguimiento de Casos')

@section('content')

<h3 class="">Seguimiento de Casos Positivos y Contactos.</h3>

<!--a class="btn btn-outline-success btn-sm mb-3" id="downloadLink" onclick="exportF(this)">Descargar en excel</a-->
<a class="btn btn-outline-success btn-sm mb-3" id="downloadLink" href="" >Descargar en excel</a>

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
                    <th>"OTRA INFORMACIÓN BLOCK CONDOMINIO TORRE NOMBRE TOMA"</th>
                    <th>CORREO ELECTRONICO</th>
                    <th>COMUNA RESIDENCIA</th>
                    <th>REGIÓN (EVENTUALMENTE PUEDE SER DE OTRA REGIÓN)</th>
                    <th>FECHA DE ÚLTIMO CONTACTO CON EL CASO DD-MM-AAAA</th>
                    <th>FECHA FIN SEGUIMIENTO (CALCULAR LOS 14 DÍAS DESPUES) DD-MM-AAAA</th>
                    <th>SEGUIMIENTO FINALIZADO</th>
                    <th>CATEGORÍA (MIRAR DICCIONARIO)</th>
                    <th>ESPECIFIQUE	FECHA DE NOTIFICACIÓN AL CONTACTO ESTRECHO</th>
                    <th>OBSERVACIÓN</th>
                    <th>PRESENTACIÓN DE SÍNTOMAS</th>
                    <th>FIEBRE</th>
                    <th>TOS</th>
                    <th>DIFICULTAD RESPIRATORIA</th>
                    <th>DOLOR MUSCULAR</th>
                    <th>DOLOR DE GARGANTA</th>
                    <th>DOLOR DE CABEZA</th>
                    <th>DIARREA</th>
                    <th>OTRO, ¿CUÁL?</th>
                    <th>ENFERMEDAD CRÓNICA (MIRAR DICCIONARIO)</th>
                    <th>COVID+</th>
                    <th>LICENCIA MÉDICA</th>
                    <th>CUARENTENA</th>
                    <th>CUARENTENA ¿POR QUÉ NO? (MIRAR DICCIONARIO)</th>
                    <th>ACCIONES (MIRAR DICCIONARIO)</th>
                    <th>OBSERVACIONES</th>
                    <th>AFILIACIÓN</th>
                    <th>NOMBRE EMPLEADOR</th>
                </tr>
            </thead>
            <tbody>

                @foreach($patients as $patient)
                  @foreach($patient->contactPatient as $contact)
                  <tr>
                    <td>°</td>
                    <td></td>
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
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{ ($contact->patient->tracing)? $contact->patient->tracing->requires_licence : '' }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{ ($contact->patient->tracing)? $contact->patient->tracing->prevision : '' }}</td>
                    <td></td>
                    <!-- <td>{{ $contact->patient->tracing }}</td> -->


                  @endforeach

                @endforeach

            </tbody>
        </table>
    </div>
</main>


@endsection

@section('custom_js_head')


@endsection
