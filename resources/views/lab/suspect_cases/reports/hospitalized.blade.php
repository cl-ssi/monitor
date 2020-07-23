@extends('layouts.app')

@section('title', 'Listado de hospitalizados' . (isset($byUserCommune) ? ' en mis comunas' : ''))

@section('content')
<h3 class="mb-3">Listado de hospitalizados {{isset($byUserCommune) ? 'en mis comunas' : '' }} </h3>

<table class="table table-sm table-bordered">
    <thead>
        <tr class="text-center">
            <th>Básico</th>
            <th>Medio</th>
            <th>UTI</th>
            <th>UCI</th>
            <th>UCI + Ventilador</th>
        </tr>
    </thead>
    <tbody>
        <tr class="text-center">
            <td>{{ $patients->where('status','Hospitalizado Básico')->count() }}</td>
            <td>{{ $patients->where('status','Hospitalizado Medio')->count() }}</td>
            <td>{{ $patients->where('status','Hospitalizado UTI')->count() }}</td>
            <td>{{ $patients->where('status','Hospitalizado UCI')->count() }}</td>
            <td>{{ $patients->where('status','Hospitalizado UCI (Ventilador)')->count() }}</td>
        </tr>
    </tbody>
</table>

<table class="table table-sm table-bordered small">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Comuna</th>
            <th>Tipo</th>
            <th>Observación</th>
        </tr>
    </thead>
    <tbody>
        @foreach($patients->reverse() as $patient)
        <tr>
            <td nowrap>
                <a href="{{ route('patients.edit',$patient)}}">
                {{ $patient->fullName }}
                </a>
            </td>
            <td nowrap>{{ ($patient->demographic AND $patient->demographic->commune) ? $patient->demographic->commune->name : ''}}</td>
            <td nowrap>{{ $patient->status }}</td>
            <td>{{ $patient->suspectCases->first()->observation }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')

@endsection
