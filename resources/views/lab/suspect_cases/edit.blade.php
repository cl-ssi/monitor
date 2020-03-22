@extends('layouts.app')

@section('title', 'Editar sospecha')

@section('content')
<h3 class="mb-3">Editar sospecha</h3>

<form method="POST" class="form-horizontal" action="{{ route('lab.suspect_cases.update', $suspectCase) }}">
    @csrf
    @method('PUT')

    <div class="form-row">
        <fieldset class="form-group col-2">
            <label for="for_sample_at">Fecha Muestra</label>
            <input type="date" class="form-control" id="for_sample_at"
                name="sample_at" value="{{ (isset($suspectCase->sample_at))? $suspectCase->sample_at->format('Y-m-d'):'' }}">
        </fieldset>

        <fieldset class="form-group col-1">
            <label for="for_epidemiological_week">Semana</label>
            <input type="number" class="form-control" id="for_epidemiological_week"
                name="epidemiological_week" value="{{ $suspectCase->epidemiological_week }}">
        </fieldset>

        <fieldset class="form-group col-2">
            <label for="for_result_ifd">Resultado IFD</label>
            <select name="result_ifd" id="for_result_ifd" class="form-control">
                <option value="Negativo" {{ ($suspectCase->result_ifd == 'Negativo')?'selected':'' }}>Negativo</option>
                <option value="INF-A" {{ ($suspectCase->result_ifd == 'INF-A')?'selected':'' }}>INF-A</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-2">
            <label for="for_epivigila">Epivigila</label>
            <input type="number" class="form-control" id="for_epivigila"
                name="epivigila" value="{{ $suspectCase->epivigila }}">
        </fieldset>

        <fieldset class="form-group col-2">
            <label for="for_pscr_sars_cov_2">PSCR SARS-CoV-2</label>
            <select name="pscr_sars_cov_2" id="for_pscr_sars_cov_2"
                class="form-control">
                <option value="negative" {{ ($suspectCase->pscr_sars_cov_2 == 'negative')?'selected':'' }}>Negativo</option>
                <option value="positive" {{ ($suspectCase->pscr_sars_cov_2 == 'positive')?'selected':'' }}>Positivo</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-2">
            <label for="for_paho_flu">PAHO FLU</label>
            <input type="number" class="form-control" name="paho_flu" id="for_paho_flu"
                value="{{ $suspectCase->paho_flu }}">
        </fieldset>

        <fieldset class="form-group col-1">
            <label for="for_age">Edad</label>
            <input type="number" class="form-control" id="for_age" name="age">
        </fieldset>

    </div>

    <div class="form-row">
        <fieldset class="form-group col-11">
            <label for="for_observation">Observación</label>
            <input type="text" class="form-control" name="observation"
                id="for_observation" value="{{ $suspectCase->observation }}">
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
