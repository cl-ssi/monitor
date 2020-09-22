@extends('layouts.app')

@section('title', 'Reporte Minsal')

@section('content')

<!-- <a class="btn btn-outline-success btn-sm mb-3" id="downloadLink" onclick="exportF(this)">Descargar en excel</a> -->

{{-- @can('Admin')
<a type="button" class="btn btn-success btn-sm mb-3" href="{{ route('lab.suspect_cases.report.ws_minsal', $laboratory) }}">Minsal <i class="fas fa-upload"></i></a>
@endcan --}}


<form method="get" class="form-inline mb-3" action="{{ route('lab.suspect_cases.reports.minsal', $laboratory) }}">

<a type="button" class="btn btn-success btn-sm mb-3" href="{{ route('lab.suspect_cases.report.exportMinsal', ['laboratory' => $laboratory, 'from' => $request->from, 'to' => $request->to] ) }}">Descargar <i class="far fa-file-excel"></i></a>
    <div class="form-group ml-3">
        <label for="for_from">Desde</label>
        <input type="datetime-local" class="form-control mx-sm-3" id="for_from" name="from"
               value="{{ Carbon\Carbon::parse($from)->format('Y-m-d\TH:i') }}">
    </div>
    <div class="form-group">
        <label for="for_to">Hasta</label>
        <input type="datetime-local" class="form-control mx-sm-3" id="for_to" name="to"
               value="{{ Carbon\Carbon::parse($to)->format('Y-m-d\TH:i') }}">
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
    </div>

</form>




<table class="table table-sm table-bordered table-responsive small text-uppercase" id="tabla_casos">
    <thead>
        <th>WS_ID</th>
        <th>ID</th>
        <th nowrap>RUN</th>
        <th nowrap>Nombre</th>
        <th nowrap>Sexo</th>
        <th nowrap>Edad</th>
        <th nowrap>Tipo muestra</th>
        <th nowrap>Resultado</th>
        <th nowrap>Fecha de toma de muestra</th>
        <th nowrap>Fecha de recepción de la muestra</th>
        <th nowrap>Fecha de resultado</th>
        <th nowrap>Hospital o establecimiento de origen</th>
        <th nowrap>Región de establecimiento de origen</th>
        <th nowrap>Laboratorio de referencia</th>
        <th nowrap>Región de laboratorio donde se procesa la muestra</th>
        <th nowrap>Teléfono de contacto de paciente</th>
        <th nowrap>Correo de contacto de paciente</th>
        <th nowrap>Dirección de contacto de paciente</th>
    </thead>
    <tbody>
        @foreach ($cases as $case)
        <tr>
            <td>{{ $case->minsal_ws_id }}</td>
            <td>{{ $case->id }}</td>
            <td nowrap>{{ $case->patient->runExport }}</td>
            <td nowrap>{{ $case->patient->fullName }}</td>
            <td nowrap>{{ strtoupper($case->patient->genderEsp) }}</td>
            <td nowrap>{{ $case->agePatient }}</td>
            <td nowrap>{{ ($case->sample_type)? $case->sample_type: '' }}</td>
            <td nowrap>{{ $case->covid19 }}</td>
            <td nowrap>{{ $case->sample_at->format('d-m-Y') }}</td>
            <td nowrap>{{ ($case->reception_at)? $case->reception_at->format('d-m-Y'): '' }}</td>
            <td nowrap>{{ ($case->pcr_sars_cov_2_at) ? $case->pcr_sars_cov_2_at->format('d-m-Y') : '' }}</td>
            <td nowrap>{{ strtoupper($case->establishment)?$case->establishment->alias.' - '.$case->origin: '' }}</td>
            <td nowrap>{{($case->establishment) ? $case->establishment->commune->region->name : ''}}</td>
            <td nowrap class="text-uppercase">{{ $laboratory->alias }}</td>
            <td nowrap>{{($case->laboratory) ? $case->laboratory->commune->region->name : ''}}</td>
            <td nowrap>{{ ($case->patient->demographic)?$case->patient->demographic->telephone:'' }}</td>
            <td nowrap>{{ ($case->patient->demographic)?$case->patient->demographic->email:'' }}</td>
            <td nowrap>{{ ($case->patient->demographic)?$case->patient->demographic->address:'' }}
                       {{ ($case->patient->demographic)? $case->patient->demographic->number:'' }}
                       {{ ($case->patient->demographic AND $case->patient->demographic->commune) ?$case->patient->demographic->commune->name:'' }}</td>
        </tr>
        @endforeach

        @foreach($externos as $case)
        <tr>
            <td></td>
            <td></td>
            <td nowrap>{{ $case->runExport }}</td>
            <td nowrap>{{ $case->fullName }}</td>
            <td nowrap>{{ strtoupper($case->gender) }}</td>
            <td nowrap>{{ $case->age }}</td>
            <td nowrap>{{ $case->sample_type }}</td>
            <td nowrap>{{ $case->result }}</td>
            <td nowrap>{{ $case->sample_at->format('d-m-Y') }}</td>
            <td nowrap>{{ ($case->reception_at)? $case->reception_at->format('d-m-Y'): '' }}</td>
            <td nowrap>{{ ($case->result_at) ? $case->result_at->format('d-m-Y') : '' }}</td>
            <td nowrap>{{ strtoupper($case->origin) }}</td>
            <td nowrap>{{ strtoupper($case->origin_commune) }}</td>
            <td nowrap class="text-uppercase">{{ last(request()->segments()) }}</td>
            <td nowrap>{{($case->laboratory) ? $case->laboratory->commune->region->name : ''}}</td>
            <td nowrap>{{ $case->telephone }}</td>
            <td nowrap>{{ $case->email }}</td>
            <td nowrap>{{ $case->address }}</td>
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
    elem.setAttribute("download", "reporte_minsal.xls"); // Choose the file name
    return false;
}

</script>
@endsection
