@extends('layouts.app')

@section('title', 'Listado de Pacientes')

@section('content')

<h3 class="mb-3"><i class="fas fa-user-injured"></i> Listado de Pacientes Positivos</h3>

<a class="btn btn-outline-success btn-sm mb-3" id="downloadLink" onclick="exportF(this)">Descargar en excel</a>

<div class="table-responsive">

<table class="table table-bordered table-sm small" id="tabla_casos">
    <thead>
        <tr>
            <th></th>
            <th>Run o (ID)</th>
            <th>Nombre</th>
            <th>Genero</th>
            <th>Fecha Nac.</th>
            <th>Comuna</th>
            <th>Dirección</th>
            <th>Teléfono</th>
            <th>Latitud</th>
            <th>Longitud</th>
        </tr>
    </thead>
    <tbody id="tablePatients">
        @foreach($patients as $patient)
        <tr>
            <td>
                @canany(['Patient: edit','Patient: demographic edit'])
                    <a href="{{ route('patients.edit', $patient) }}">
                        Editar
                    </a>
                @endcan
            </td>
            <td class="text-center">{{ $patient->identifier }}</td>
            <td nowrap>
                {{ $patient->fullName }}
            </td>
            <td>{{ $patient->sexEsp }}</td>
            <td nowrap>{{ ($patient->birthday)?$patient->birthday->format('d-m-Y'):'' }}</td>
            <td nowrap class="{{ (!$patient->demographic)?'alert-danger':''}}">{{ ($patient->demographic)?$patient->demographic->commune:'' }}</td>
            <td>
                {{ ($patient->demographic)?$patient->demographic->address:'' }}
                {{ ($patient->demographic)?$patient->demographic->number:'' }}
            </td>
            <td>{{ ($patient->demographic)?$patient->demographic->telephone:'' }}</td>
            <td>{{ ($patient->demographic)?str_replace('.',',',$patient->demographic->latitude):'' }}</td>
            <td>{{ ($patient->demographic)?str_replace('.',',',$patient->demographic->longitude):'' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>


@endsection

@section('custom_js')
<script type="text/javascript">
$(document).ready(function(){
    $("main").removeClass("container");
});

function exportF(elem) {
    var table = document.getElementById("tabla_casos");
    var html = table.outerHTML;
    var html_no_links = html.replace(/<a[^>]*>|<\/a>/g, "");//remove if u want links in your table
    var url = 'data:application/vnd.ms-excel,' + escape(html_no_links); // Set your html table into url
    elem.setAttribute("href", url);
    elem.setAttribute("download", "casos_coordenadas.xls"); // Choose the file name
    return false;
}
</script>
@endsection
