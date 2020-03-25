@extends('layouts.app')

@section('title', 'Editar Paciente')

@section('content')
<h3 class="mb-3">Editar Paciente</h3>

<form method="POST" class="form-horizontal" action="{{ route('patients.update',$patient) }}">
    @csrf
    @method('PUT')

    <div class="form-row">
        <fieldset class="form-group col-10 col-md-2">
            <label for="for_run">Run</label>
            <input type="text" class="form-control" id="for_run" name="run"
                value="{{ $patient->run }}">
        </fieldset>

        <fieldset class="form-group col-2 col-md-1">
            <label for="for_dv">DV</label>
            <input type="text" class="form-control" id="for_dv" name="dv"
                value="{{ $patient->dv }}">
        </fieldset>

        <fieldset class="form-group col-12 col-md-4">
            <label for="for_other_identification">Otra identificación</label>
            <input type="text" class="form-control" id="for_other_identification"
                placeholder="Extranjeros sin run" name="other_identification"
                value="{{ $patient->other_identification }}">
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_gender">Genero</label>
            <select name="gender" id="for_gender" class="form-control">
                <option value="male"
                    {{($patient->gender == 'male')?'selected':''}}>
                    Masculino
                </option>
                <option value="female"
                    {{($patient->gender == 'female')?'selected':''}}>
                    Femenino
                </option>
                <option value="other"
                    {{($patient->gender == 'other')?'selected':''}}>
                    Otro
                </option>
                <option value="unknown"
                    {{($patient->gender == 'unknown')?'selected':''}}>
                    Desconocido
                </option>
            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_birthday">Fecha Nacimiento</label>
            <input type="date" class="form-control" id="for_birthday"
                name="birthday" value="{{ $patient->birthday }}">
        </fieldset>
    </div>

    <div class="form-row">
        <fieldset class="form-group col-12 col-md-4">
            <label for="for_name">Nombres</label>
            <input type="text" class="form-control" id="for_name" name="name"
                value="{{ $patient->name }}">
        </fieldset>

        <fieldset class="form-group col-12 col-md-4">
            <label for="for_fathers_family">Apellido Paterno</label>
            <input type="text" class="form-control" id="for_fathers_family"
                name="fathers_family" value="{{ $patient->fathers_family }}">
        </fieldset>

        <fieldset class="form-group col-12 col-md-4">
            <label for="for_mothers_family">Apellido Materno</label>
            <input type="text" class="form-control" id="for_mothers_family"
                name="mothers_family" value="{{ $patient->mothers_family }}">
        </fieldset>

    </div>

    <div class="card mb-3">
        <div class="card-body">

        @if($patient->demographic)
            @include('patients.demographic.edit')
        @else
            @include('patients.demographic.create')
        @endif

        </div>
    </div>


    <button type="submit" class="btn btn-primary">Guardar</button>

    <a class="btn btn-outline-secondary" href="{{ route('patients.index') }}">
        Cancelar
    </a>

</form>
@can('Patient: delete')
<form method="POST" class="form-horizontal" action="{{ route('patients.destroy',$patient) }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger float-right">Borrar</button>

</form>
@endcan


<table class="table table-sm small text-muted mt-3">
    <thead>
        <tr>
            <th colspan="4">Historial de cambios</th>
        </tr>
        <tr>
            <th>Modelo</th>
            <th>Fecha</th>
            <th>Usuario</th>
            <th>Modificaciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($patient->logs as $log)
        <tr>
            <td>{{ $log->model_type }}</td>
            <td>{{ $log->created_at }}</td>
            <td>{{ $log->user->name }}</td>
            <td>
                @foreach($log->diferencesArray as $key => $diference)
                    {{ $key }} => {{ $diference}} <br>
                @endforeach
            </td>
        </tr>
        @endforeach
        @if($patient->demographic)
            @foreach($patient->demographic->logs as $log)
            <tr>
                <td>{{ $log->model_type }}</td>
                <td>{{ $log->created_at }}</td>
                <td>{{ $log->user->name }}</td>
                <td>
                    @foreach($log->diferencesArray as $key => $diference)
                        {{ $key }} => {{ $diference}} <br>
                    @endforeach
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
@endsection

@section('custom_js')

@endsection
