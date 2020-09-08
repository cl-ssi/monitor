@extends('layouts.app')

@section('title', 'Listado de casos')

@section('content')

<h3 class="mb-3"><i class="fas fa-lungs-virus"></i>
    @if($laboratory)
        Examenes del laboratorio {{ $laboratory->alias }}
    @else
        Listado de todos los exámenes 
    @endif
</h3>

<div class="row">
    @can('SuspectCase: create')
    <div class="col-10 col-sm-6">
        <a class="btn btn-primary mb-3" href="{{ route('lab.suspect_cases.admission') }}">
            Crear nueva sospecha
        </a>
    </div>
    @endcan
</div>
<div class="table-responsive">
<table class="table table-sm table-bordered" >
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
            <td>{{ $cases['total'] }}</td>
            <th class="text-danger">{{ $cases['positivos'] }}</th>
            <td>{{ $cases['negativos'] }}</td>
            <td>{{ $cases['pendientes'] }}</td>
            <td>{{ $cases['rechazados'] }}</td>
            <td>{{ $cases['indeterminados'] }}</td>
        </tr>
    </tbody>
</table>
</div>

<!--------------------------------->
@if($laboratory)
<div class="row row align-items-end"> <!---START ROW PRINCIPAL--->
  <div class="col-12 col-sm-12 col-md-6"> <!-- START COL 1 PRINCIPAL -->
      <div class="row align-items-end">
        <div class="col-12 col-sm-12">
            <a type="button" class="btn btn-sm btn-success mb-3" href="{{ route('lab.suspect_cases.export', $laboratory->id) }}">Descargar <i class="far fa-file-excel"></i></a>
        <!--</div>
        <div class="col-6 col-sm-3">-->
            <a class="btn btn-outline-info btn-sm mb-3" href="{{ route('lab.suspect_cases.reports.minsal',$laboratory) }}">Reporte MINSAL</a>
        <!--</div>
        <div class="col-6 col-sm-6 float-left">-->
            <a class="btn btn-outline-info btn-sm mb-3" href="{{ route('lab.suspect_cases.reports.seremi',$laboratory) }}">Reporte SEREMI</a>
        <!--</div>
        <div class="col-6 col-sm-3">-->
            <a class="btn btn-outline-info btn-sm mb-3" href="{{ route('lab.suspect_cases.report.estadistico_diario_covid19',$laboratory) }}">Reporte estadistico diario</a>
        </div>
      </div><!---END row--->
      <div class="row">
          <div class="col-12 col-sm-12 col-md-12" align="right">
              @include('lab.suspect_cases.partials.search_id')
          </div>
      </div><!---END row--->

  </div><!---END COL 1 PRINCIPAL--->

@else
    <div class="row row align-items-end"> <!---START ROW PRINCIPAL--->
        <div class="col-12 col-sm-12 col-md-6"> <!-- START COL 1 PRINCIPAL -->
            <div class="row align-items-end">
                <div class="col-12 col-sm-12 col-md-6">
                    <input type="month" class="form-control mb-3" id="for_month"
                           name="month" value="{{ Carbon\Carbon::now()->format('Y-m') }}" required>
                </div>
                <div class="col-12 col-sm-12 col-md-6">
                    <a type="button" class="btn btn-success mb-3" id="btn_download"
                       href="{{ route('lab.suspect_cases.export', ['all', Carbon\Carbon::now()->format('Y-m')]) }}">Descargar
                        <i class="far fa-file-excel"></i></a>
                </div>
            </div><!---END row--->
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12" align="right">
                    @include('lab.suspect_cases.partials.search_id')
                </div>
            </div><!---END row--->
        </div><!---END COL 1 PRINCIPAL--->

@endif

    <div class="col-12 col-sm-12 col-md-6"> <!-- START COL 2 PRINCIPAL -->
      <div class="row"> <!-- START ROW 1 -->
        <div class="col-12 mb-3">
          <form method="get" action="{{ route('lab.suspect_cases.index',$laboratory) }}">
            <input type="checkbox" name="positivos" id="chk_positivos" v="Positivos" {{ ($request->positivos)?'checked':'' }} /> Positivos
            <input type="checkbox" name="negativos" id="chk_negativos" v="Negativos" {{ ($request->negativos)?'checked':'' }} /> Negativos
            <input type="checkbox" name="pendientes" id="chk_pendientes" v="Pendientes" {{ ($request->pendientes)?'checked':'' }} /> Pendientes
            <input type="checkbox" name="rechazados" id="chk_rechazados" v="Rechazados" {{ ($request->rechazados)?'checked':'' }} /> Rechazados
            <input type="checkbox" name="indeterminados" id="chk_indeterminados" v="Indeterminados" {{ ($request->indeterminados)?'checked':'' }} /> Indeterminados
        </div>
      </div> <!-- END ROW 1 -->
      <div class="row"> <!-- START ROW 2 -->
        <div class="col-12">
            <div class="input-group mb-3">
              <div class="input-group-prepend"><span class="input-group-text">Búsqueda</span></div>
              <input class="form-control" type="text" name="text" value="{{$request->text}}" placeholder="Rut / Nombre">
              <div class="input-group-append"><button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button></div>
            </div>
          </div>
      </div> <!-- END ROW 2 -->
          </form>
</div><!---END COL 2 PRINCIPAL--->
</div><!---END ROW PRINCIPAL--->



<!--------------------------------->



<div class="table-responsive">
<table class="table table-sm table-bordered" id="tabla_casos">
    <thead>
        <tr>
            <th nowrap>° Monitor</th>
            <th>Fecha muestra</th>
            <th>Origen</th>
            <th>Nombre</th>
            <th>RUN</th>
            <th>Edad</th>
            <th>Sexo</th>
            <th class="alert-danger">PCR SARS-Cov2</th>
            <th>Resultado IFD</th>
            <th>Ext. Lab</th>
            <th>Epivigila</th>
            <th>Fecha de Resultado</th>
            <th>Estado</th>
            <th>Observación</th>
        </tr>
    </thead>
    <tbody id="tableCases">
        @foreach($suspectCases as $case)
        <tr class="row_{{$case->covid19}} {{ ($case->pcr_sars_cov_2 == 'positive')?'table-danger':''}}">
            <td class="text-center">
                {{ $case->id }}<br>
                <small>{{ $case->laboratory->alias }}</small>
                @canany(['SuspectCase: edit','SuspectCase: tecnologo'])
                <a href="{{ route('lab.suspect_cases.edit', $case) }}" class="btn_edit"><i class="fas fa-edit"></i></a>
                @endcan
                <small>{{ $case->minsal_ws_id }}</small>
            </td>
            <td nowrap class="small">{{ (isset($case->sample_at))? $case->sample_at->format('Y-m-d'):'' }}</td>
            <td>
                {{ ($case->establishment) ? $case->establishment->alias . ' - ': '' }}
                {{ $case->origin }}
            </td>
            <td>
                @if($case->patient)
                <a class="link" href="{{ route('patients.edit', $case->patient) }}">
                    {{ $case->patient->fullName }}
                    @if($case->gestation == "1") <img align="center" src="{{ asset('images/pregnant.png') }}" width="24"> @endif
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
                @if($case->file)
                    <a href="{{ route('lab.suspect_cases.download', $case->id) }}"
                    target="_blank"><i class="fas fa-paperclip"></i>&nbsp
                </a>
                @endif

                @if ($case->laboratory->pdf_generate == 1 && $case->pcr_sars_cov_2 <> 'pending')
                <a href="{{ route('lab.print', $case) }}"
                    target="_blank"><i class="fas fa-paperclip"></i>&nbsp
                </a>
                @endif
            </td>
            <td class="{{ ($case->result_ifd <> 'Negativo' AND $case->result_ifd <> 'No solicitado')?'text-danger':''}}">{{ $case->result_ifd }} {{ $case->subtype }}</td>
            <td>{{ $case->external_laboratory }}</td>
            <td>{{ $case->epivigila }}</td>
            <td>{{ ($case->pcr_sars_cov_2_at)?$case->pcr_sars_cov_2_at->format('d-m-Y'):'' }}</td>
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
    $('#for_month').on('change', function() {
        $('#btn_download').attr('href', '{{route('lab.suspect_cases.export', 'all')}}' + '/' + this.value);
    });
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
