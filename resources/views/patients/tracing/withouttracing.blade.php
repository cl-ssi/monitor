@extends('layouts.app')

@section('title', 'Sin Seguimiento')

@section('content')

<h3 class="mb-3"><i class="fa fa-eye-slash"></i> Pacientes sin seguimiento en las ultimas dos semanas</h3>

<div class="row">
    <div class="col-8 col-sm-9">
        <form method="GET" class="form-horizontal" action="{{ route('patients.tracings.withouttracing') }}">
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
                <th>Fecha Nac.</th>
                <th>Comuna</th>
                <!--th>Dirección</th-->
                <!--th>Teléfono</th-->
                <!--th>Email</th-->
                <th>Fecha Creación en Sistema</th>
                <th>Fecha PSR COV AT</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patients as $patient)
            <tr>
                <td>
                    <a href="{{ route('patients.edit',$patient)}}" target="_blank">
                        Editar
                    </a>
                </td>
                <td>{{ $patient->identifier }}</td>
                <td>{{ $patient->fullName }}</td>
                <td>{{ ($patient->birthday)?$patient->birthday->format('d-m-Y'):'' }}</td>
                <td>{{ ($patient->demographic AND $patient->demographic->commune) ?
                    $patient->demographic->commune->name : '' }}</td>
                <!--td>
                  {{ ($patient->demographic)?$patient->demographic->address:'' }}
                  {{ ($patient->demographic)?$patient->demographic->number:'' }}
              </td-->
                <!--td>{{ ($patient->demographic)?$patient->demographic->telephone:'' }}</td-->
                <!--td>{{ ($patient->demographic)?$patient->demographic->email:'' }}</td-->
                <td>{{ $patient->created_at->format('d-m-Y')  }}</td>
                <td>{{ ($patient->suspectCases->last()->pcr_sars_cov_2_at) ?
                    $patient->suspectCases->last()->pcr_sars_cov_2_at->format('d-m-Y H:i') : '' }}</td>

            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $patients->links() }}
</div>



@endsection

@section('custom_js')

@endsection
