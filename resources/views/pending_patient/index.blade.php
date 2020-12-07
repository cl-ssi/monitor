@extends('layouts.app')

@section('title', 'Pacientes pendientes')

@section('content')

    @if ($errors->any())
        <div class="alert alert-warning">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-4">
            <h3 class="mb-3"><i class="fas fa-phone"></i> Pacientes Pendientes</h3>
        </div>
        <div class="col-4"></div>


        <div class="col-4">
            <a href="{{route('pending_patient.export_excel_by_status', $selectedStatus)}}" class="btn btn-sm btn-success float-right"> <i class="fas fa-print"></i> Imprimir</a>
        </div>
    </div>

    <form method="GET" action="{{ route('pending_patient.index') }}">
        <div class="row align-items-end mb-3">

            <div class="col-12 col-md-4 col-lg-4">
                <div class="input-group">

                    <label for="for_status"></label>
                    <select name="status" id="for_status" class="form-control" required>
                        <option value="not_contacted" {{($selectedStatus == 'not_contacted' ? 'selected' : '')}}> Sin
                            contactar
                        </option>
                        <option
                            value="updated_information" {{($selectedStatus == 'updated_information' ? 'selected' : '')}}>
                            Informacion Actualizada
                        </option>
                        <option value="contacted" {{($selectedStatus == 'contacted' ? 'selected' : '')}}> Contactado
                        </option>
                    </select>


                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary float-left d-print-none"><i
                                class="fa fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4 col-lg-4 float-right">
            </div>

            <div class="col-12 col-md-4 col-lg-4 float-right">
                <label></label>
                <input class="form-control" type="text" id="textoFiltro" placeholder="Ingresar nombre para filtrar">
            </div>

        </div>

    </form>


    <table class="table table-sm table-bordered table-responsive" id="tabla_casos">
        <thead style="width: 100%;">
        <tr>
            <th nowrap>° ID</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Comuna</th>
            <th>Fono</th>
            <th>Email</th>
            <th>Editar</th>
        </tr>
        </thead>
        <tbody id="tableCases" style="width: 100%;">
        @if($pendingPatients)
            @foreach($pendingPatients as $patient)
                <tr>
                    <td class="text-center">{{ $patient->id }}</td>
                    <td>{{ ($patient->name) ? "$patient->name $patient->fathers_family $patient->mothers_family" : '' }}</td>
                    <td> {{($patient->address) ? $patient->address : ''}} </td>

                    <td>{{ $patient->commune->name }}</td>
                    <td> {{$patient->telephone}} </td>
                    <td> {{$patient->email}} </td>

                    <td>
                        <a class="link btn btn-sm btn-primary" href="{{ route('pending_patient.edit', $patient) }}">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>

    {{--{{ $suspectCases->appends(request()->query())->links() }}--}}

@endsection

@section('custom_js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#textoFiltro").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                value = value.normalize('NFD').replace(/[\u0300-\u036f]/g, "");
                $("#tabla_casos tr").filter(function () {
                    var tableValue = $(this).text().toLowerCase();
                    tableValue = tableValue.normalize('NFD').replace(/[\u0300-\u036f]/g, "");
                    $(this).toggle(tableValue.indexOf(value) > -1)
                });
            });
        });
    </script>
@endsection
