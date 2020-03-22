@extends('layouts.app')

@section('title', 'Listado de Pacientes')

@section('content')

<h3 class="mb-3">Listado de Pacientes</h3>

<div class="row">
    <div class="col-4 col-sm-3">
        <a class="btn btn-primary mb-4" href="{{ route('patients.create') }}">
            Crear Paciente
        </a>
    </div>
    <div class="col-7 col-sm-9 alert alert-primary" role="alert">
        Para buscar presione Ctrl+F
    </div>
</div>





<table class="table table-sm">
    <thead>
        <tr>
            <th>Run o (ID)</th>
            <th>Nombres</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Genero</th>
            <th>Fecha Nac.</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($patients as $patient)
        <tr>
            <td class="text-center">{{ $patient->identifier }}</td>
            <td>{{ $patient->name }}</td>
            <td>{{ $patient->fathers_family }}</td>
            <td>{{ $patient->mothers_family }}</td>
            <td>{{ $patient->genderEsp }}</td>
            <td>{{ $patient->birthday }}</td>
            <td> <a href="{{ route('patients.edit',$patient) }}">Editar</a> </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')

@endsection
