@extends('layouts.app')

@section('title', 'Listado de Pacientes')

@section('content')

<h3 class="mb-3"><i class="fas fa-user-injured"></i> Buscador de Pacientes</h3>

<div class="row">
    @can('Patient: create')
    <div class="col-12 col-md-3">
        <a class="btn btn-primary mb-4" href="{{ route('patients.create') }}">
            <i class="fas fa-plus"></i> Crear Paciente
        </a>
    </div>
    @endcan
    @can('Patient: list')
    <div class="col-12 col-md-9">
        <form method="GET" class="form-horizontal" action="{{ route('patients.index') }}">
            <div class="input-group mb-sm-0">
                <input class="form-control" type="text" name="search" autocomplete="off" id="for_search" style="text-transform: uppercase;" placeholder="RUN (sin dígito verificador) / OTRA IDENTIFICACION / NOMBRE" value="{{$request->search}}" required>
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
                </div>
            </div>
        </form>
    </div>
    @endcan
    @can('DialysisCenter: user')
    <div class="col-12 col-md-9">
        <form method="GET" class="form-horizontal" action="{{ route('patients.dialysis.index', $establishment) }}">
            <div class="input-group mb-sm-0">
                <input class="form-control" type="text" name="search" autocomplete="off" id="for_search" style="text-transform: uppercase;" placeholder="RUN (sin dígito verificador) / OTRA IDENTIFICACION / NOMBRE" value="{{$request->search}}" required>
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
                </div>
            </div>
        </form>
    </div>
    @endcan
</div>

<hr>
@if(!empty($patients))
<div class="table-responsive">
    <table class="table table-sm table-bordered table-striped small">
        <thead>
            <tr class="text-center">
                <th></th>
                <th>Run o (ID)</th>
                <th>Nombre</th>
                <th>Genero</th>
                <th>Fecha Nac.</th>
                <th>Comuna</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Email</th>
                @can('DialysisCenter: user')
                <th>Añadir Paciente a Centro de Dialisis</th>
                @endcan
            </tr>
        </thead>
        <tbody>
            @forelse($patients as $patient)
            <tr>
                <td>
                    @canany(['Patient: edit','Patient: demographic edit','Patient: show'])
                    <a href="{{ route('patients.edit', $patient) }}">
                        Editar/Ver
                    </a>

                    @endcan
                </td>
                <td class="text-right" nowrap>{{ $patient->identifier }}</td>
                <td class="text-right">
                    {{ $patient->fullName }}
                </td>
                <td>{{ $patient->sexEsp }}</td>
                <td nowrap>{{ ($patient->birthday)?$patient->birthday->format('d-m-Y'):'' }}</td>
                <td class="text-right" nowrap>{{ ($patient->demographic AND $patient->demographic->commune)  ?$patient->demographic->commune->name:'' }}</td>
                <td class="text-right">
                    {{ ($patient->demographic)?$patient->demographic->address:'' }}
                    {{ ($patient->demographic)?$patient->demographic->number:'' }}
                </td>
                <td class="text-right">
                    {{ ($patient->demographic)?$patient->demographic->telephone:'' }}
                </td>
                <td class="text-right">{{ ($patient->demographic)?$patient->demographic->email:'' }}</td>
                @can('DialysisCenter: user')
                <td>
                    <form method="POST" class="form-horizontal" action="{{ route('lab.suspect_cases.dialysis.store') }}">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="patient_id" value="{{$patient->id}}">
                        <input type="hidden" name="establishment_id" value="{{$establishment->id}}">
                        <button type="submit" class="btn btn-primary" onclick="return confirm('¿Está seguro que desea Añadir al Paciente {{$patient->fullname}} al {{$establishment->alias}}? ' )">Añadir</button>
                    </form>
                </td>
                @endcan
            </tr>
            @empty
            <p class="bg-danger text-white p-1">Debe ingresar valor a buscar</p>
            @endforelse
        </tbody>
    </table>


    {{ $patients->appends(request()->query())->links() }}
</div>
@endif


@endsection

@section('custom_js')
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

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
</script> -->
@endsection