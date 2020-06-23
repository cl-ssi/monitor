@extends('layouts.app')

@section('title', 'Listado de fallecidos')

@section('content')
<h3 class="mb-3">Listado de fallecidos</h3>

<table class="table table-sm table-bordered small">
    <thead>
        <tr>
            <th>Ct</th>
            <th>Nombre</th>
            <th>Comuna</th>
            <th>Fecha de defunción</th>
            <th>Observación</th>
        </tr>
    </thead>
    <tbody>
        @foreach($patients->reverse() as $key => $patient)
        <tr>
            <td>{{ ++$key }}</td>
            <td nowrap>
                <a href="{{ route('patients.edit',$patient)}}">
                {{ $patient->fullName }}
                </a>
            </td>
            <td>{{ ($patient->demographic AND $patient->demographic->commune) ? $patient->demographic->commune->name : ''}}</td>
            <td nowrap>{{ ($patient->deceased_at) ? $patient->deceased_at->format('Y-m-d') : '' }}</td>
            <td>{{ $patient->suspectCases->first()->observation }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')

@endsection
