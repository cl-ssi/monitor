@extends('layouts.app')

@section('title', 'Listado de hospitalizados')

@section('content')
<h3 class="mb-3">Listado de hospitalizados</h3>

<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Comuna</th>
            <th></th>
            <th>Tipo</th>
        </tr>
    </thead>
    <tbody>
        @foreach($patients->reverse() as $patient)
        <tr>
            <td>
                <a href="{{ route('patients.edit',$patient)}}">
                {{ $patient->fullName }}
                </a>
            </td>
            <td>{{ ($patient->demographic AND $patient->demographic->commune) ? $patient->demographic->commune->name : ''}}</td>
            <td></td>
            <td>{{ $patient->status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')

@endsection
