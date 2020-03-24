@extends('layouts.app')

@section('title', 'Listado de casos')

@section('content')

<h3 class="mb-3">Listado de casos</h3>

<div class="row">
    @can('SuspectCase: create')
    <div class="col-5 col-sm-3">
        <a class="btn btn-primary mb-3" href="{{ route('lab.suspect_cases.create') }}">
            Crear nueva sospecha
        </a>
    </div>
    @endcan
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
            <th class="text-danger">{{ $suspectCases->where('pscr_sars_cov_2','positive')->count() }}</th>
            <td>{{ $suspectCases->where('pscr_sars_cov_2','negative')->count() }}</td>
            <td>{{ $suspectCases->where('pscr_sars_cov_2','pending')->count() }}</td>
            <td>{{ $suspectCases->count() }}</td>
        </tr>
    </tbody>
</table>

<a class="btn btn-outline-success btn-sm mb-3" id="downloadLink" onclick="exportF(this)">Descargar en excel</a>

<div class="table-responsive">
<table class="table table-sm table-bordered" id="tabla_casos">
    <thead>
        <tr>
            <th>°</th>
            <th>Fecha muestra</th>
            <th>Origen</th>
            <th>Nombre</th>
            <th>RUN</th>
            <th>Edad</th>
            <th>Resultado IFD</th>
            <th>Sem</th>
            <th>Epivigila</th>
            <th class="alert-danger">PCR SARS-Cov2</th>
            <th>PAHO FLU</th>
            <th>Estado</th>
            <th>Observación</th>
            @can('SuspectCase: edit')
            <th class="action_th"></th>
            @endcan
        </tr>
    </thead>
    <tbody>
        @foreach($suspectCases as $case)
        <tr class="{{ ($case->pscr_sars_cov_2 == 'positive')?'table-danger':''}}">
            <td>{{ $case->id }}</td>
            <td nowrap>{{ (isset($case->sample_at))? $case->sample_at->format('Y-m-d'):'' }}</td>
            <td>{{ $case->origin }}</td>
            <td>{{ $case->patient->fullName }}</td>
            <td class="text-center" nowrap>{{ $case->patient->identifier }}</td>
            <td>{{ $case->age }}</td>
            <td class="{{ ($case->result_ifd <> 'Negativo')?'text-danger':''}}">{{ $case->result_ifd }} {{ $case->subtype }}</td>
            <td>{{ $case->epidemiological_week }}</td>
            <td>{{ $case->epivigila }}</td>
            <td>{{ $case->pscr_sars_cov_2 }}</td>
            <td>{{ $case->paho_flu }}</td>
            <td>{{ $case->status }}</td>
            <td class="text-muted small">{{ $case->observation }}</td>
            @can('SuspectCase: edit')
            <td class="action_td"><a href="{{ route('lab.suspect_cases.edit', $case) }}" class="btn_edit">Editar</a></td>
            @endcan
        </tr>
        @endforeach
    </tbody>
</table>
</div>


@endsection

@section('custom_js')
<script type="text/javascript">
function exportF(elem) {
    var table = document.getElementById("tabla_casos");
    var html = table.outerHTML;
    var html_no_links = html.replace(/<a[^>]*>|<\/a>/g, "");//remove if u want links in your table
    var url = 'data:application/vnd.ms-excel,' + escape(html_no_links); // Set your html table into url
    elem.setAttribute("href", url);
    elem.setAttribute("download", "casos_sospecha.xls"); // Choose the file name
    return false;
}
</script>
@endsection
