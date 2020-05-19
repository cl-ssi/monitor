@extends('layouts.app')

@section('title', 'Listado de Pacientes')

@section('content')

<h3 class="mb-3"><i class="fas fa-user-injured"></i> Listado de Pacientes</h3>

<div class="row">
    @can('Patient: create')
    <div class="col-4 col-sm-3">
        <a class="btn btn-primary mb-4" href="{{ route('patients.create') }}">
            Crear Paciente
        </a>
    </div>
    @endcan
    <div class="col-7 col-md-6" role="alert">
        <form method="GET" class="form-horizontal" action="{{ route('patients.index') }}">

            <div class="input-group">
                <input type="text" class="form-control" name="search" id="for_search"
                    placeholder="Nombre o Apellido o Rut" >
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit" id="button-addon">Buscar</button>
                </div>
            </div>

        </form>
    </div>
</div>


<table class="table table-sm table-responsive small">
    <thead>
        <tr>
            <th></th>
            <th>Run o (ID)</th>
            <th>Nombre</th>
            <th>Genero</th>
            <th>Fecha Nac.</th>
            <th>Comuna</th>
            <th>Dirección</th>
            <th>Teléfono</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody id="tablePatients">
        @foreach($patients as $patient)
        <tr class="{{ ($patient->suspectCases->where('pscr_sars_cov_2','positive')->first())?'alert-danger':'' }}">
            <td>
                @canany(['Patient: edit','Patient: demographic edit'])
                    <a href="{{ route('patients.edit', $patient) }}">
                        Editar
                    </a>
                @endcan
            </td>
            <td class="text-center">{{ $patient->identifier }}</td>
            <td>
                {{ $patient->fullName }}
            </td>
            <td>{{ $patient->sexEsp }}</td>
            <td nowrap>{{ ($patient->birthday)?$patient->birthday->format('d-m-Y'):'' }}</td>
            <td nowrap>{{ ($patient->demographic)?$patient->demographic->commune:'' }}</td>
            <td>
                {{ ($patient->demographic)?$patient->demographic->address:'' }}
                {{ ($patient->demographic)?$patient->demographic->number:'' }}
            </td>
            <td>
                {{ ($patient->demographic)?$patient->demographic->telephone:'' }}
            </td>
            <td>{{ ($patient->demographic)?$patient->demographic->email:'' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $patients->links() }}

@endsection

@section('custom_js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $("main").removeClass("container");

    $("#inputSearch").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#tablePatients tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>
@endsection
