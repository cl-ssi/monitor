@extends('layouts.app')

@section('title', 'Examenes sin Recepción')

@section('content')
<h3 class="mb-3">Examenes no Recepcionados</h3>

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
            <th>Estado</th>
            <th>Observación</th>
        </tr>
    </thead>
    <tbody id="tableCases">
        @foreach($cases as $case)
        <tr>
            <td class="text-center">
                <small>
                {{ $case->id }}
                </small>
                @canany(['SuspectCase: edit','SuspectCase: tecnologo'])
                <a href="{{ route('lab.suspect_cases.edit', $case) }}" class="btn_edit"><i class="fas fa-edit"></i></a>
                @endcan
                <small>{{ $case->minsal_ws_id }}</small>
            </td>
            <td class="text-center">{{ (isset($case->sample_at))? $case->sample_at->format('Y-m-d'):'' }}</td>
            <td class="text-center">{{ $case->origin }}</td>
            <td class="text-center">{{ $case->patient->fullname }}</td>
            <td class="text-center">{{ $case->patient->getIdentifierAttribute() }}</td>
            <td class="text-center">{{ $case->age }}</td>
            <td class="text-center">{{ strtoupper($case->gender[0]) }}</td>
            <td class="text-center">{{ $case->patient->status }}</td>
            <td class="text-muted small">{{ $case->observation }}</td>
        </tr>
        @endforeach
      </tbody>
  </table>
  </div>

@endsection

@section('custom_js')

@endsection
