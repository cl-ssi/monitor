@extends('layouts.app')

@section('title', 'Nueva sospecha')

@section('content')
<h3 class="mb-3">Nueva sospecha</h3>

<form method="POST" class="form-horizontal" action="{{ route('lab.suspect_cases.store') }}">
    @csrf
    @method('POST')
    <div class="form-row">
        <fieldset class="form-group col-2">
            <label for="for_run">Run</label>
            <input type="text" class="form-control" id="for_run" name="run">
        </fieldset>

        <fieldset class="form-group col-1">
            <label for="for_dv">DV</label>
            <input type="text" class="form-control" id="for_dv" name="dv">
        </fieldset>

        <fieldset class="form-group col-3">
            <label for="for_other_identification">Otra identificación</label>
            <input type="text" class="form-control" id="for_other_identification"
                placeholder="Extranjeros sin run" name="other_identification">
        </fieldset>

        <fieldset class="form-group col-2">
            <label for="for_gender">Genero</label>
            <select name="gender" id="for_gender" class="form-control">
                <option value="male">Masculino</option>
                <option value="female">Femenino</option>
                <option value="other">Otro</option>
                <option value="unknown">Desconocido</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-2">
            <label for="for_birthday">Fecha Nacimiento</label>
            <input type="date" class="form-control" id="for_birthday"
                name="birthday">
        </fieldset>

        <fieldset class="form-group col-1">
            <label for="for_age">Edad</label>
            <input type="number" class="form-control" id="for_age" name="age">
        </fieldset>
    </div>

    <div class="form-row">
        <fieldset class="form-group col-3">
            <label for="for_name">Nombres</label>
            <input type="text" class="form-control" id="for_name" name="name">
        </fieldset>

        <fieldset class="form-group col-2">
            <label for="for_fathers_family">Apellido Paterno</label>
            <input type="text" class="form-control" id="for_fathers_family"
                name="fathers_family">
        </fieldset>

        <fieldset class="form-group col-2">
            <label for="for_mothers_family">Apellido Materno</label>
            <input type="text" class="form-control" id="for_mothers_family"
                name="mothers_family">
        </fieldset>
    </div>

    <div class="form-row">

        <fieldset class="form-group col-2">
            <label for="for_sample_at">Fecha Muestra</label>
            <input type="date" class="form-control" id="for_sample_at"
                name="sample_at" required>
        </fieldset>

        <fieldset class="form-group col-1">
            <label for="for_epidemiological_week">Semana</label>
            <input type="number" class="form-control" id="for_epidemiological_week"
                name="epidemiological_week">
        </fieldset>

        <fieldset class="form-group col-2">
            <label for="for_result_ifd">Resultado IFD</label>
            <select name="result_ifd" id="for_result_ifd" class="form-control">
                <option value="Negativo">Negativo</option>
                <option value="INF-A">INF-A</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-2">
            <label for="for_epivigila">Epivigila</label>
            <input type="number" class="form-control" id="for_epivigila"
                name="epivigila">
        </fieldset>

        <fieldset class="form-group col-2">
            <label for="for_pscr_sars_cov_2">PSCR SARS-CoV-2</label>
            <select name="pscr_sars_cov_2" id="for_pscr_sars_cov_2"
                class="form-control">
                <option value="negative">Negativo</option>
                <option value="positive">Positivo</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-2">
            <label for="for_paho_flu">PAHO FLU</label>
            <input type="number" class="form-control" name="paho_flu" id="for_paho_flu"
                required>
        </fieldset>

    </div>

    <div class="form-row">
        <fieldset class="form-group col-11">
            <label for="for_observation">Observación</label>
            <input type="text" class="form-control" name="observation"
                id="for_observation">
        </fieldset>

    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>

    <a class="btn btn-outline-secondary" href="{{ route('lab.suspect_cases.index') }}">
        Cancelar
    </a>
</form>

@endsection

@section('custom_js')

@endsection
