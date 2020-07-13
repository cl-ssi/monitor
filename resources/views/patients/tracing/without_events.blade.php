@extends('layouts.app')

@section('title', 'Seguimientos sin eventos')

@section('content')
<h3 class="mb-3">Pacientes en cuarentena sin seguimiento (ningún evento)</h3>

<table class="table table-sm small">
    <thead>
        <tr>
            <th>°</th>
            <th>ID</th>
            <th>Identificador</th>
            <th>Nombre</th>
            <th>Comuna Residencia</th>
            <th>Estab. Toma de muestra</th>
            <th>Inicio Cuarentena</th>
            <th>Termino Cuarentena</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tracingsWithoutEvents->reverse() as $key => $tracing)
        <tr>
            <td>{{ $key-- }}</td>
            <td>
                <a href="{{ route('patients.edit', $tracing->patient) }}">
                {{ $tracing->patient->id }}
                </a>
            </td>
            <td>{{ $tracing->patient->identifier }}</td>
            <td>{{ $tracing->patient->fullName }}</td>
            <td>{{ $tracing->patient->demographic->commune->name }}</td>
            <td>{{ ($tracing->establishment) ? $tracing->establishment->alias : '' }}</td>
            <td>{{ $tracing->quarantine_start_at->format('Y-m-d') }}</td>
            <td nowrap>{{ $tracing->quarantine_end_at->format('Y-m-d') }} ({{ $tracing->quarantine_start_at->diffInDays(now()) }})</td>
            <td>{{ ($tracing->patient->status == '') ? 'Ambulatorio' : $tracing->patient->status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>


@endsection

@section('custom_js')

@endsection
