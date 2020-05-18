@extends('layouts.app')

@section('title', 'Bandeja de recepción')

@section('content')

<h3 class="mb-3"><i class="fas fa-lungs-virus"></i> Bandeja de recepción</h3>

<div class="row">
    <div class="col-3">
        <table class="table table-sm table-bordered">
            <thead>
                <tr class="text-center">
                    <th>Exámenes por recepcionar</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center">
                    <td>{{ $suspectCases->count() }} pendientes</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-3">
        @include('lab.suspect_cases.partials.search_id')
    </div>
    <div class="col-5">
        <h3>Tu Laboraotrio: {{ Auth::user()->laboratory->name }}</h3>
    </div>
</div>



<table class="table table-sm table-bordered table-responsive" id="tabla_casos">
    <thead>
        <tr>
            <th>°</th>
            <th></th>
            <th>Fecha muestra</th>
            <th>Origen</th>
            <th>Nombre</th>
            <th>Identificador</th>
            <th>Edad</th>
            <th>Sexo</th>
            <th class="alert-danger">PCR SARS-Cov2</th>
            <th>Observación</th>
        </tr>
    </thead>
    <tbody id="tableCases">
        @foreach($suspectCases as $case)
        <tr class="row_{{$case->covid19}} {{ ($case->pscr_sars_cov_2 == 'positive')?'table-danger':''}}">
            <td class="text-center">{{ $case->id }}</td>
            <td>
                @can('SuspectCase: reception')
                    <form method="POST" class="form-inline" action="{{ route('lab.suspect_cases.reception', $case) }}">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-inbox"></i></button>
                    </form>
                @endcan
            </td>
            <td nowrap class="small">@date($case->sample_at)</td>
            <td>{{ $case->origin }}</td>
            <td>
                @if($case->patient)
                <a class="link" href="{{ route('patients.edit', $case->patient) }}">
                    {{ $case->patient->fullName }}
                </a>
                @endif
            </td>
            <td class="text-center" nowrap>
                @if($case->patient)
                {{ $case->patient->identifier }}
                @endif
            </td>
            <td>{{ $case->age }}</td>
            <td>{{ strtoupper($case->gender[0]) }}</td>
            <td>{{ $case->covid19 }}</td>
            <td class="text-muted small">{{ $case->observation }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $suspectCases->appends(request()->query())->links() }}

@endsection

@section('custom_js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){

});

function exportF(elem) {
    var table = document.getElementById("tabla_casos");
    var html = table.outerHTML;
    var html_no_links = html.replace(/<a[^>]*>|<\/a>/g, "");//remove if u want links in your table
    var url = 'data:application/vnd.ms-excel,' + escape(html_no_links); // Set your html table into url
    elem.setAttribute("href", url);
    elem.setAttribute("download", "casos_sospecha_laboratorio_hetg.xls"); // Choose the file name
    return false;
}
</script>
@endsection
