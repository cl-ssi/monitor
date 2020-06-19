@extends('layouts.app')

@section('title', 'Reporte COVID-19')

@section('content')

<h3 class="">Seguimiento de Casos Positivos</h3>

    @if($patientsNoDemographic->count() > 0)
        @include('lab.suspect_cases.reports.partials.table_case_tracing',['patients' => $patientsNoDemographic,
                                                                            'max_cases' => $max_cases_no_demographic,
                                                                            'max_cases_inmuno' => $max_cases_inmuno_no_demographic])
    @endif

    <br/>

    <a class="btn btn-outline-success btn-sm mb-3" href="{{ route('lab.suspect_cases.reports.case_tracing.export') }}">
        Descargar en excel
    </a>

    @include('lab.suspect_cases.reports.partials.table_case_tracing')

    {{ $patients->links() }}
@endsection

@section('custom_js')
<script type="text/javascript">
$(document).ready(function(){
    $("main").removeClass("container");
});
</script>
@endsection
