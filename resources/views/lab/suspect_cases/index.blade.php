@extends('layouts.app')

@section('title', 'Listado de casos')

@section('content')

<h3 class="mb-3"><i class="fas fa-lungs-virus"></i> Listado de todos los exámenes</h3>

<div class="row">
    @can('SuspectCase: create')
    <div class="col-5 col-sm-3">
        <a class="btn btn-primary mb-3" href="{{ route('lab.suspect_cases.create') }}">
            Crear nueva sospecha
        </a>
    </div>
    @endcan

</div>

<table class="table table-sm table-bordered">
    <thead>
        <tr class="text-center">
            <th>Exámenes enviados a análisis</th>
            <th>Exámenes positivos</th>
            <th>Exámenes negativos</th>
            <th>Exámenes pendientes</th>
            <th>Exámenes rechazados</th>
            <th>Exámenes indeterminados</th>
        </tr>
    </thead>
    <tbody>
        <tr class="text-center">
            <td>{{ $suspectCasesTotal->count() }}</td>
            <th class="text-danger">{{ $suspectCasesTotal->where('pscr_sars_cov_2','positive')->count() }}</th>
            <td>{{ $suspectCasesTotal->where('pscr_sars_cov_2','negative')->count() }}</td>
            <td>{{ $suspectCasesTotal->where('pscr_sars_cov_2','pending')->count() }}</td>
            <td>{{ $suspectCasesTotal->where('pscr_sars_cov_2','rejected')->count() }}</td>
            <td>{{ $suspectCasesTotal->where('pscr_sars_cov_2','undetermined')->count() }}</td>
        </tr>
    </tbody>
</table>

<a type="button" class="btn btn-success" href="{{ route('lab.suspect_cases.export', 'all') }}">Descargar <i class="far fa-file-excel"></i></a>

<div align="right">
<form method="get" action="{{ route('lab.suspect_cases.index') }}">

  <input type="checkbox" name="positivos" id="chk_positivos" v="Positivos" {{ ($request->positivos)?'checked':'' }} /> Positivos
  <input type="checkbox" name="negativos" id="chk_negativos" v="Negativos" {{ ($request->negativos)?'checked':'' }} /> Negativos
  <input type="checkbox" name="pendientes" id="chk_pendientes" v="Pendientes" {{ ($request->pendientes)?'checked':'' }} /> Pendientes
  <input type="checkbox" name="rechazados" id="chk_rechazados" v="Rechazados" {{ ($request->rechazados)?'checked':'' }} /> Rechazados
  <input type="checkbox" name="indeterminados" id="chk_indeterminados" v="Indeterminados" {{ ($request->indeterminados)?'checked':'' }} /> Indeterminados

  <div class="input-group mb-3 col-12 col-sm-5">
    <div class="input-group-prepend">
        <span class="input-group-text">Búsqueda</span>
    </div>

    <input class="form-control" type="text" name="text" value="{{$request->text}}" placeholder="Rut / Nombre">
    <div class="input-group-append">
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
    </div>
  </div>

</form>
@include('lab.suspect_cases.partials.search_id')
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
            <th class="alert-danger">PCR SARS-Cov2</th>
            <th>Resultado IFD</th>
            <th>Lab</th>
            <th>Epivigila</th>
            <th>PAHO FLU</th>
            <th>Estado</th>
            <th>Observación</th>
        </tr>
    </thead>
    <tbody id="tableCases">
        @foreach($suspectCases as $case)
        <tr class="row_{{$case->covid19}} {{ ($case->pscr_sars_cov_2 == 'positive')?'table-danger':''}}">
            <td class="text-center">
                {{ $case->id }}<br>
                <small>{{ $case->laboratory->name }}</small>
                @can('SuspectCase: edit')
                <a href="{{ route('lab.suspect_cases.edit', $case) }}" class="btn_edit"><i class="fas fa-edit"></i></a>
                @endcan
            </td>
            <td nowrap class="small">{{ (isset($case->sample_at))? $case->sample_at->format('Y-m-d'):'' }}</td>
            <td>{{ $case->origin }}</td>
            <td>
                @if($case->patient)
                <a class="link" href="{{ route('patients.edit', $case->patient) }}">
                    {{ $case->patient->fullName }}
                    @if($case->gestation == "on") <img align="center" src="{{ asset('images/pregnant.png') }}" width="24"> @endif
                    @if($case->close_contact == "1") <img align="center" src="{{ asset('images/contact.png') }}" width="24"> @endif
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
            <td class="{{ ($case->result_ifd <> 'Negativo' AND $case->result_ifd <> 'No solicitado')?'text-danger':''}}">{{ $case->result_ifd }} {{ $case->subtype }}</td>
            <td>{{ $case->procesingLab }}</td>
            <td>{{ $case->epivigila }}</td>
            <td>{{ $case->paho_flu }}</td>
            <td>{{ $case->patient->status }}</td>
            <td class="text-muted small">{{ $case->observation }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

{{ $suspectCases->appends(request()->query())->links() }}

@endsection

@section('custom_js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    // $("#inputSearch").on("keyup", function() {
    //     var value = $(this).val().toLowerCase();
    //     $("#tableCases tr").filter(function() {
    //         $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    //     });
    // });
    // //oculta segun checkbox
    // $("#chk_positivos").change(function(){
    //     $(".row_Positivo").toggle();
    // });
    // $("#chk_negativos").change(function(){
    //     var self = this;
    //     $(".row_Negativo").toggle();
    // });
    // $("#chk_pendientes").change(function(){
    //     var self = this;
    //     $(".row_Pendiente").toggle();
    // });
    // $("#chk_rechazados").change(function(){
    //     var self = this;
    //     $(".row_Rechazado").toggle();
    // });
    // $("#chk_indeterminados").change(function(){
    //     var self = this;
    //     $(".row_Indeterminado").toggle();
    // });
});

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
