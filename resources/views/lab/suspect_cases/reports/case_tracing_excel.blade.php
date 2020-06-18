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
                    <th colspan="6">Contactos</th>
                </tr>
                <tr class="text-center">
                    <th>N°</th>
                    <th>RUN o IDENTIFICADOR</th>
                    <th>NOMBRE UNIDO</th>
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
                    <th>REGIÓN (EVENTUALMENTE PUEDE SER DE OTRA REGIÓN)<th>
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
                    <td>{{ $contact->contact_patient->name }}</td>
                    <td>{{ $contact->contact_patient->fathers_family }}</td>
                    <td>{{ $contact->contact_patient->mothers_family }}</td>
                    <td>{{ $contact->contact_patient->fullName }}</td>
                    <td>{{ $contact->contact_patient->birthday->format('d-m-Y') }}</td>
                    <td>{{ $contact->contact_patient->demographic->telephone }}</td>
                    <td>{{ $contact->contact_patient->demographic->telephone2 }}</td>
                  </tr>
                  @endforeach
                @endforeach

            </tbody>
        </table>
    </div>

</main>


@endsection

@section('custom_js_head')


@endsection
