@extends('layouts.app')

@section('title', 'Listado de casos')

@section('content')

<h3 class="mb-3"><i class="fas fa-lungs-virus"></i> Listado de casos Laboratorio UNAP</h3>

<div class="row">
    @can('SuspectCase: create')
    <div class="col-5 col-sm-3">
        <a class="btn btn-primary mb-3" href="{{ route('lab.suspect_cases.create') }}">
            Crear nueva sospecha
        </a>
    </div>
    @endcan
    <div class="col-7 col-sm-4" role="alert">
        <input class="form-control" id="inputSearch" type="text" placeholder="Buscar">
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

<div align="right">
  <input type="checkbox" name="positivos" id="chk_positivos" v="Positivos" checked/> Positivos
  <input type="checkbox" name="negativos" id="chk_negativos" v="Negativos" checked/> Negativos
  <input type="checkbox" name="pendientes" id="chk_pendientes" v="Pendientes" checked/> Pendientes
</div>

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
            <th>Sexo</th>
            <th>Resultado IFD</th>
            <th class="alert-danger">PCR SARS-Cov2</th>
            <th>Sem</th>
            <th>Epivigila</th>
            <th>PAHO FLU</th>
            <th>Estado</th>
            <th>Observación</th>
            @can('SuspectCase: edit')
            <th class="action_th"></th>
            @endcan
        </tr>
    </thead>
    <tbody id="tableCases">
        @foreach($suspectCases as $case)
        <tr class="row_{{$case->covid19}} {{ ($case->pscr_sars_cov_2 == 'positive')?'table-danger':''}}">
            <td class="text-center">{{ $case->id }}<br><small>{{ $case->laboratory->name }}</small></td>
            <td nowrap class="small">{{ (isset($case->sample_at))? $case->sample_at->format('Y-m-d'):'' }}</td>
            <td>{{ $case->origin }}</td>
            <td>
                @if($case->patient)
                <a class="link" href="{{ route('patients.edit', $case->patient) }}">
                    {{ $case->patient->fullName }}
                    @if($case->gestation == "on") <img align="center" src="{{ asset('images/pregnant.png') }}" width="24"> @endif
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
            <td class="{{ ($case->result_ifd <> 'Negativo' AND $case->result_ifd <> 'No procesado')?'text-danger':''}}">{{ $case->result_ifd }} {{ $case->subtype }}</td>
            <td>{{ $case->covid19 }}
                @if($case->files->first())
                <a href="{{ route('lab.suspect_cases.download', $case->files->first()->id) }}"
                    target="_blank"><i class="fas fa-paperclip"></i>&nbsp
                </a>
                @endif
                @if ($case->laboratory_id == 2 && $case->pscr_sars_cov_2 <> 'pending')
                <a href="{{ route('lab.print', $case) }}"
                    target="_blank"><i class="fas fa-paperclip"></i>&nbsp
                </a>
                @endif
            </td>
            <td>{{ $case->epidemiological_week }}</td>
            <td>{{ $case->epivigila }}</td>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $("#inputSearch").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#tableCases tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    //oculta segun checkbox
    $("#chk_positivos").change(function(){
        $(".row_Positivo").toggle();
    });
    $("#chk_negativos").change(function(){
        var self = this;
        $(".row_Negativo").toggle();
    });
    $("#chk_pendientes").change(function(){
        var self = this;
        $(".row_Pendiente").toggle();
    });
});

function exportF(elem) {
    var table = document.getElementById("tabla_casos");
    var html = table.outerHTML;
    var html_no_links = html.replace(/<a[^>]*>|<\/a>/g, "");//remove if u want links in your table
    var url = 'data:application/vnd.ms-excel,' + escape(html_no_links); // Set your html table into url
    elem.setAttribute("href", url);
    elem.setAttribute("download", "casos_sospecha_laboratorio_unap.xls"); // Choose the file name
    return false;
}
</script>
@endsection
