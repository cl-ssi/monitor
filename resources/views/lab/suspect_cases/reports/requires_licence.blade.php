@extends('layouts.app')

@section('title', 'Pacientes con Licencia Medica')

@section('content')
<h3 class="mb-3">Pacientes que requieren Licencia Médica en seguimiento</h3>

<table class="table table-sm table-bordered small">
    <thead>
        <tr class="text-center">
            <th>#</th>
            <th>Nombre</th>
            <th>Comuna de Residencia Paciente</th>
            <th>Establecimiento de Seguimiento</th>
        </tr>
    </thead>
    <tbody>
        @foreach($patients->reverse() as $key => $patient)
        <tr>
            <td class="text-right">{{ ++$key }}</td>
            <td nowrap class="text-right">
                <a href="{{ route('patients.edit',$patient)}}">
                {{ $patient->fullName }}
                </a>
            </td>
            <td class="text-right">{{ ($patient->demographic AND $patient->demographic->commune) ? $patient->demographic->commune->name : ''}}</td>
            <td class="text-right">{{ ($patient->tracing) ? $patient->tracing->establishment->alias : '' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')

@endsection
