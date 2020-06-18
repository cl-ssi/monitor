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
    <div class="col-8 col-sm-9">
        <form method="GET" class="form-horizontal" action="{{ route('patients.index') }}">
            <div class="input-group mb-sm-0">
                <div class="input-group-prepend">
                    <span class="input-group-text">Búsqueda</span>
                </div>

                <input class="form-control" type="text" name="search" autocomplete="off" id="for_search" style="text-transform: uppercase;" placeholder="RUN (sin dígito verificador) / OTRA IDENTIFICACION / NOMBRE" value="{{$request->search}}" required>

                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<hr>

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
          </tr>
      </thead>
      <tbody>
          @foreach($patients as $patient)
          <tr>
              <td>
                  @canany(['Patient: edit','Patient: demographic edit'])
                      <a href="{{ route('patients.edit', $patient) }}">
                          Editar
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
          </tr>
          @endforeach
      </tbody>
  </table>


{{ $patients->appends(request()->query())->links() }}
</div>

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
