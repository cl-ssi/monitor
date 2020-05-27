@extends('layouts.app')

@section('title', 'Reporte Seremi')

@section('content')


<a type="button" class="btn btn-success btn-sm mb-3" href="{{ route('lab.suspect_cases.report.exportSeremi', $laboratory) }}">Descargar <i class="far fa-file-excel"></i></a>

<table class="table table-sm table-bordered table-responsive small" id="tabla_casos">
    <thead>
        <th>ID</th>
        <th nowrap>Nombre completo</th>
        <th nowrap>RUN o Ident.</th>
        <th nowrap>Procedencia</th>
        <th nowrap>Edad</th>
        <th nowrap>Embarazo (EG)</th>
        <th nowrap>Tipo de muestra</th>
        <th nowrap>Semana epidemiológica</th>
        <th nowrap>Fecha de toma de muestra</th>
        <th nowrap>Fecha de envío de muestra</th>
        <th nowrap>Institución que procesa la muestra</th>
        <th nowrap>Fecha resultado</th>
        <th nowrap>Resultado</th>
    </thead>
    <tbody>
        @foreach ($cases as $case)
        <tr>
            <td>{{ $case->id }}</td>
            <td nowrap>{{ $case->patient->fullName }}</td>
            <td nowrap class="text-right">{{ $case->patient->identifier}}</td>
            <td nowrap class="text-uppercase">{{ $case->origin }}</td>
            <td nowrap>{{ $case->age }}</td>
            <td nowrap>{{ ($case->gestation == 1 )?'X':'' }}</td>
            <td nowrap>{{ $case->sample_type }}</td>
            <td nowrap>{{ $case->epidemiological_week }}</td>
            <td nowrap>{{ $case->sample_at->format('d-m-Y') }}</td>
            <td nowrap>{{ $case->SentExternalAt }}</td>
            <td nowrap>{{ $case->ProcesingLab }}</td>
            <td nowrap>{{ ($case->pscr_sars_cov_2_at)?$case->pscr_sars_cov_2_at->format('d-m-Y'):'' }}</td>
            <td nowrap>{{ $case->covid19 }}</td>
        </tr>
        @endforeach
    </tbody>


</table>


@endsection

@section('custom_js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
function exportF(elem) {
    var table = document.getElementById("tabla_casos");
    var html = table.outerHTML;
    var html_no_links = html.replace(/<a[^>]*>|<\/a>/g, "");//remove if u want links in your table
    var url = 'data:application/vnd.ms-excel,' + escape(html_no_links); // Set your html table into url
    elem.setAttribute("href", url);
    elem.setAttribute("download", "reporte_seremi.xls"); // Choose the file name
    return false;
}

</script>
@endsection
