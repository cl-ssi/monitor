
@extends('layouts.app')

@section('title', 'Reporte Seguimiento de Casos por Comuna')

@section('content')

<h3 class="mb-3">Reporte de Seguimiento por Comuna</h3>

<a class="btn btn-outline-success btn-sm mb-3" id="downloadLink" onclick="exportF(this)">Descargar en excel</a>

<form method="get" class="form-inline mb-3" action="{{ route('lab.suspect_cases.reports.tracingbycommunes') }}">
  <div class="form-group">
      <label for="for_date">Fecha:</label>
      <input type="date" class="form-control mx-sm-3" id="for_date" name="date" value="{{ $request->date }}" max="{{ date('Y-m-d') }}">
  </div>
  <div class="form-group">
      <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
  </div>
</form>



<div class="table-responsive">
    <table class="table table-sm table-bordered table-responsive text-center align-middle" id="tabla_estado_residencias">
        <thead>
            <tr class="text-center">
                <th rowspan="2">FECHA</th>
                <th rowspan="2">COMUNA</th>
                <th colspan="2">CASOS</th>
                <th colspan="2">SEGUIMIENTO</th>
            </tr>
            <tr class="text-center">                
                <th>POSITIVOS</th>
                <th>CAR</th>
                <th>EN CURSO</th>
                <th>TERMINADO</th>
            </tr>
        </thead>
            <tbody>
            @if($request->date)
            @foreach($communes as $commune)
            <tr>
                    <td>{{ $request->date }}</td>
                    <td>{{ $report[$commune->id]['Comuna'] }}</td>
                    <td>{{ $report[$commune->id]['positives'] }}</td>
                    <td>{{ $report[$commune->id]['car'] }}</td>
                    <td>{{ $report[$commune->id]['curso'] }}</td>
                    <td>{{ $report[$commune->id]['terminado'] }}</td>
                    
            </tr>            
            @endforeach
            @endif
            </tbody>
        </table>
</div>




@endsection

@section('custom_js')

<script type="text/javascript">
    function exportF(elem) {
        var table = document.getElementById("tabla_files");
        var html = table.outerHTML;
        var html_no_links = html.replace(/<a[^>]*>|<\/a>/g, "");//remove if u want links in your table
        var url = 'data:application/vnd.ms-excel,' + escape(html_no_links); // Set your html table into url
        elem.setAttribute("href", url);
        elem.setAttribute("download", "seguimiento.xls"); // Choose the file name
        return false;
    }
</script>

@endsection

@section('custom_js_head')

@endsection
