@extends('layouts.app')

@section('title', 'Reporte COVID-19')

@section('content')

<h3 class="">Seguimiento de Casos Positivos</h3>

</main><main class="">

    @if($patientsNoDemographic->count() > 0)
        <h4>Casos sin domicilio</h4>
        @include('lab.suspect_cases.reports.partials.table_case_tracing',['patients' => $patientsNoDemographic,
                                                                            'max_cases' => $max_cases_no_demographic,
                                                                            'max_cases_inmuno' => $max_cases_inmuno_no_demographic])
    @endif

    <br/>

    <div class="form-inline">
        <h4 style="padding-right:10px">Casos con domicilio</h4>
        <a class="btn btn-outline-success btn-sm mb-3" id="downloadLink" style="line" href="{{ route('lab.suspect_cases.reports.case_tracing.export') }}" >Descargar en excel</a>
    </div>

    @include('lab.suspect_cases.reports.partials.table_case_tracing')

@endsection
