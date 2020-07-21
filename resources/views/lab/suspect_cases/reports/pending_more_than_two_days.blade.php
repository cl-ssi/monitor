@extends('layouts.app')

@section('title', 'Pacientes con Licencia Medica')

@section('content')
<h3 class="mb-3">Reporte de casos pendientes mayores a 48 hrs:</h3>

<br>

<div class="table-responsive">
    <table class="table table-sm table-bordered table-striped small">
      <thead>
        <tr class="text-center">
          <th></th>
          <th>Monitor #</th>
          <th>Fecha de Recepción</th>
          <th>Origen</th>
          <th>Paciente</th>
          <th>RUN</th>
          <th>Edad</th>
          <th>Sexo</th>
          <th>Estado Muestra</th>
        </tr>
      </thead>
      <tbody>
        @foreach($suspectCases as $key => $suspectCase)
        <tr class="text-right">
          <td>{{ $key + 1 }}</td>
          <td>{{ $suspectCase->id }}</td>
          <td>{{ $suspectCase->reception_at->format('d-m-Y H:i:s') }}</td>
          <td>{{ ($suspectCase->establishment) ? $suspectCase->establishment->alias . ' - ': '' }} {{ $suspectCase->origin }}</td>
          <td>{{ $suspectCase->patient->fullName }}</td>
          <td>{{ $suspectCase->patient->identifier }}</td>
          <td>{{ $suspectCase->age }}</td>
          <td>{{ strtoupper($suspectCase->gender[0]) }}</td>
          <td>{{ $suspectCase->Covid19 }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
</div>




@endsection

@section('custom_js')

@endsection
