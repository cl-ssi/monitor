@extends('layouts.app')

@section('title', 'Listado de Pacientes')

@section('content')

<h3 class="mb-3">Listado de Pacientes</h3>

<div class="row">
    @can('Patient: create')
    <div class="col-4 col-sm-3">
        <a class="btn btn-primary mb-4" href="{{ route('patients.create') }}">
            Crear Paciente
        </a>
    </div>
    @endcan
    <div class="col-7 col-sm-9 alert alert-primary" role="alert">
        Para buscar presione Ctrl+F
    </div>
</div>


<div class="table-responsive">
<table class="table table-sm">
    <thead>
        <tr>
            <th>Run o (ID)</th>
            <th>Nombre</th>
            <th>Genero</th>
            <th>Fecha Nac.</th>
            <th>Dirección/Comuna</th>
            <th>Teléfono/Email</th>
            @can('Patient: edit')
            <th></th>
            @endcan
        </tr>
    </thead>
    <tbody>
        @foreach($patients as $patient)
        <tr>
            <td class="text-center">{{ $patient->identifier }}</td>
            <td>{{ $patient->fullName }}</td>
            <td>{{ $patient->genderEsp }}</td>
            <td>{{ $patient->birthday }}</td>
            <td class="small">
                {{ ($patient->demographic)?$patient->demographic->address:'' }}<br>
                {{ ($patient->demographic)?$patient->demographic->commune:'' }}
            </td>
            <td class="small">
                {{ ($patient->demographic)?$patient->demographic->telephone:'' }}<br>
                {{ ($patient->demographic)?$patient->demographic->email:'' }}
            </td>
            @canany(['Patient: edit','Demographic: edit'])
            <td> <a href="{{ route('patients.edit',$patient) }}">Editar</a> </td>
            @endcan
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection

@section('custom_js')

@endsection
