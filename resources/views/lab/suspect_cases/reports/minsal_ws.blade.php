@extends('layouts.app')

@section('title', 'Reporte Minsal')

@section('content')

<!-- <a class="btn btn-outline-success btn-sm mb-3" id="downloadLink" onclick="exportF(this)">Descargar en excel</a> -->
{{-- <a type="button" class="btn btn-success btn-sm mb-3" href="{{ route('lab.suspect_cases.report.exportMinsal', $laboratory) }}">Descargar <i class="far fa-file-excel"></i></a> --}}
@can('Admin')
<a type="button" class="btn btn-success btn-sm mb-3" href="{{ route('lab.suspect_cases.report.ws_minsal', $laboratory) }}">Minsal <i class="fas fa-upload"></i></a>
@endcan

<table class="table table-sm table-bordered table-responsive small text-uppercase" id="tabla_casos">
    <thead>
        {{-- <th>WS_ID</th> --}}
        <th nowrap>Cod. muestra cliente</th>
        <th nowrap>Run responsable</th>
        <th nowrap>Cod DEIS</th>
        <th nowrap>Run médico</th>
        <th nowrap>Run paciente</th>
        <th nowrap>Nombres</th>
        <th nowrap>Ap. Paterno</th>
        <th nowrap>Ap. Materno</th>
        <th nowrap>F.Nacimiento</th>
        <th nowrap>Comuna DEIS</th>
        <th nowrap>Dirección</th>
        <th nowrap>Teléfono</th>
        <th nowrap>Tipo Doc.</th>
        <th nowrap>Cod. País origen</th>
        <th nowrap>Pasaporte</th>
        <th nowrap>Género</th>
        <th nowrap>Previsión</th>
        <th nowrap>Fecha Muestra</th>
        <th nowrap>Técnica muestra</th>
        <th nowrap>Tipo muestra</th>
        <th nowrap>F. Resultado</th>
        <th nowrap>F. Resultado</th>
        <th nowrap>Archivo</th>
    </thead>

    <tbody>
        @foreach ($cases as $case)
        <tr>
            {{-- <td>{{ $case->minsal_ws_id }}</td> --}}
            <td>{{ $case->id }}</td>
            <td nowrap>--</td>
            <td nowrap>02-100</td>
            <td nowrap>{{$case->run_medic}}</td>
            <td nowrap>{{ $case->patient->runExport }}</td>
            <td nowrap>{{ $case->patient->name }}</td>
            <td nowrap>{{ $case->patient->fathers_family }}</td>
            <td nowrap>{{ $case->patient->mothers_family }}</td>
            <td nowrap>{{ strtoupper($case->patient->birthday) }}</td>
            <td nowrap>{{ $case->commune_code_deis }}</td>
            <td nowrap>{{ $case->patient->demographic->address . " " . $case->patient->demographic->number }}</td>
            <td nowrap>{{ $case->patient->demographic->telephone }}</td>
            <td nowrap>{{ $case->paciente_tipodoc }}</td>
            <td nowrap>{{ $case->paciente_ext_paisorigen }}</td>
            <td nowrap>{{ $case->patient->other_identification }}</td>
            <td nowrap>{{ $case->genero }}</td>
            <td nowrap>FONASA</td>
            <td nowrap>{{ $case->sample_at }}</td>
            <td nowrap>RT-PCR</td>
            <td nowrap>{{ $case->sample_type }}</td>
            <td nowrap>{{ $case->pscr_sars_cov_2_at }}</td>
            <td nowrap>{{ $case->pscr_sars_cov_2 }}</td>
            <td nowrap>
                @if($case->files->first())
                    <a href="{{ route('lab.suspect_cases.download', $case->files->first()->id) }}"
                        target="_blank"><i class="fas fa-paperclip"></i>&nbsp
                    </a>
                @else
                    <a href="{{ route('lab.print', $case) }}"
                        target="_blank"><i class="fas fa-paperclip"></i>&nbsp
                    </a>
                @endif
            </td>
        </tr>
        @endforeach
        {{-- @foreach($externos as $case)
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
            <td nowrap>TARAPACÁ</td>
            <td nowrap>{{ $case->telephone }}</td>
            <td nowrap>{{ $case->email }}</td>
            <td nowrap>{{ $case->address }}</td>
        </tr>
        @endforeach --}}
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
