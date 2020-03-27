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
    <div class="col-7 col-sm-4" role="alert">
        <input class="form-control" id="inputSearch" type="text" placeholder="Buscar">
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
    <tbody id="tablePatients">
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $("#inputSearch").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#tablePatients tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>
@endsection
