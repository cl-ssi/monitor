
@extends('layouts.app')

@section('title', 'Reporte Seguimiento de Casos por Comuna')

@section('content')

<form method="get" class="form-inline mb-3" action="{{ route('patients.tracings.reportbycommune') }}">
  <div class="form-group">
      <label for="for_date">Fecha:</label>
      <input type="date" class="form-control mx-sm-3" id="for_date" name="date" value="{{ $request->date }}" max="{{ date('Y-m-d') }}">
  </div>
  <div class="form-group">
      <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
  </div>
</form>

</main>

<main>
    <table class="table table-sm table-bordered table-responsive small" id="tabla_files" >
        <thead>
            <tr class="text-center">
                <th rowspan="2">Fecha</th>
                <th rowspan="2">Comuna</th>
                <th colspan="2">Casos</th>
                <th colspan="2">Seguimiento</th>
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
                    <td>{{ $commune->name }}</td>
                    <td>{{ $commune->count = $patients->where('demographic.commune_id',$commune->id)->count() }}</td>
                    
                    
            </tr>            
            @endforeach
            @endif
            </tbody>
        </table>   
</main>



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
