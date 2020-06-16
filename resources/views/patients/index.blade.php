@extends('layouts.app')

@section('title', 'Listado de Pacientes')

@section('content')

<h3 class="mb-3"><i class="fas fa-user-injured"></i> Listado de Pacientes</h3>

<div class="row">
    @can('Patient: create')
    <div class="col-4 col-sm-3">
        <a class="btn btn-primary mb-4" href="{{ route('patients.create') }}">
            <i class="fas fa-plus"></i> Crear Paciente
        </a>
    </div>
    @endcan
</div>

<form method="GET" class="form-horizontal" action="{{ route('patients.index', 'search_true') }}">
  <div class="input-group mb-sm-0">
      <div class="input-group-prepend">
          <span class="input-group-text">Búsqueda</span>
      </div>

      <input class="form-control" type="text" name="search" autocomplete="off" id="for_search" style="text-transform: uppercase;" placeholder="RUN (sin dígito verificador) / OTRA IDENTIFICACION / NOMBRE" value="{{$request->search}}" required>

      <input class="form-control" type="text" name="dv" id="for_dv" style="text-transform: uppercase;" placeholder="DV" readonly hidden>

      <div class="input-group-append">
          <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
      </div>
  </div>
</form>

<hr>

<table class="table table-sm table-bordered text-center table-striped small">
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
        </tr>
    </thead>
    <tbody>
      @if($patients != NULL)
        @foreach($patients as $patient)
        <tr>
            <td>
                @canany(['Patient: edit','Patient: demographic edit'])
                    <a href="{{ route('patients.edit', $patient) }}">
                        Editar
                    </a>
                @endcan
            </td>
            <td class="text-rigth" nowrap>{{ $patient->identifier }}</td>
            <td class="text-rigth">
                {{ $patient->fullName }}
            </td>
            <td>{{ $patient->sexEsp }}</td>
            <td nowrap>{{ ($patient->birthday)?$patient->birthday->format('d-m-Y'):'' }}</td>
            <td nowrap>{{ ($patient->demographic AND $patient->demographic->commune)  ?$patient->demographic->commune->name:'' }}</td>
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
      @endif
    </tbody>
</table>

{{ $patients->appends(request()->query())->links() }}


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
