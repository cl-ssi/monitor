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
        </div>
    </div>

    <form method="GET" action="{{ route('pending_patient.index') }}">
        <div class="row align-items-end mb-3">
            <div class="col-12 col-md-4 col-lg-4">
                <label for="for_status">Estado</label>
                <select name="status" id="for_status" class="form-control" required>
                    <option value="not_contacted" {{($selectedStatus == 'not_contacted' ? 'selected' : '')}}> Sin contactar</option>
                    <option value="updated_information" {{($selectedStatus == 'updated_information' ? 'selected' : '')}}> Informacion Actualizada</option>
                    <option value="contacted" {{($selectedStatus == 'contacted' ? 'selected' : '')}}> Contactado</option>
                </select>

            </div>

            <div class="col-12 col-md-4 col-lg-4">
                <button type="submit" class="btn btn-primary float-left d-print-none"><i class="fa fa-search"></i> Filtrar</button>
            </div>

        </div>

    </form>


    <table class="table table-sm table-bordered table-responsive" id="tabla_casos">
        <thead>
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
        <tbody id="tableCases">
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
@endsection
