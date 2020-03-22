@extends('layouts.app')

@section('title', 'Listado de casos')

@section('content')

<h3 class="mb-3">Listado de casos</h3>

<div class="row">
    <div class="col-5 col-sm-3">
        <a class="btn btn-primary mb-3" href="{{ route('lab.suspect_cases.create') }}">
            Crear nueva sospecha
        </a>
    </div>
    <div class="col-7 col-sm-9 alert alert-primary" role="alert">
        Para buscar presione Ctrl+F
    </div>
</div>



<table class="table table-sm table-bordered">
    <thead>
        <tr class="text-center">
            <th></th>
            <th>Total positivos</th>
            <th>Total negativos</th>
            <th>Total sin resultados</th>
            <th>Total enviados a análisis</th>
        </tr>
    </thead>
    <tbody>
        <tr class="text-center">
            <td>Casos</td>
            <td>{{ $suspectCases->where('pscr_sars_cov_2','positive')->count() }}</td>
            <td>{{ $suspectCases->where('pscr_sars_cov_2','negative')->count() }}</td>
            <td>{{ $suspectCases->where('pscr_sars_cov_2','')->count() }}</td>
            <td>{{ $suspectCases->count() }}</td>
        </tr>
    </tbody>
</table>

<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>°</th>
            <th>Fecha muestra</th>
            <th>Nombre</th>
            <th>RUN</th>
            <th>Edad</th>
            <th>Resultado IFD</th>
            <th>Semana</th>
            <th>Epivigila</th>
            <th class="alert-danger">PSCR SARS COV 2</th>
            <th>PAHO FLU</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($suspectCases as $case)
        <tr>
            <td>{{ $case->id }}</td>
            <td>{{ (isset($case->sample_at))? $case->sample_at->format('Y-m-d'):'' }}</td>
            <td>{{ $case->patient->fullName }}</td>
            <td class="text-center">{{ $case->patient->identifier }}</td>
            <td>{{ $case->age }}</td>
            <td class="{{ ($case->result_ifd <> 'Negativo')?'text-danger':''}}">{{ $case->result_ifd }}</td>
            <td>{{ $case->epidemiological_week }}</td>
            <td>{{ $case->epivigila }}</td>
            <td>{{ $case->pscr_sars_cov_2 }}</td>
            <td>{{ $case->paho_flu }}</td>
            <td><a href="{{ route('lab.suspect_cases.edit', $case) }}">Editar</a></td>
        </tr>
        <tr class="d-none d-sm-table-row">
            <td class=""></td>
            <td class="text-muted" colspan="10">{{ $case->observation }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')

@endsection
