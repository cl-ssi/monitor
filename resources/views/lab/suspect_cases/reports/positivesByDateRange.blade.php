@extends('layouts.app')

@section('title', 'Positivos por fecha')

@section('content')

<h3 class="mb-3">Casos positivos por rango de fechas</h3>

<form method="get" class="form-inline mb-3" action="{{ route('lab.suspect_cases.reports.positivesByDateRange') }}">
    <div class="form-group ml-3">
        <label for="for_from">Desde</label>
        <input type="date" class="form-control mx-sm-3" id="for_from" name="from"
               value="{{ Carbon\Carbon::parse($from)->format('Y-m-d') }}">
    </div>

    <div class="form-group">
        <label for="for_to">Hasta</label>
        <input type="date" class="form-control mx-sm-3" id="for_to" name="to"
               value="{{ Carbon\Carbon::parse($to)->format('Y-m-d') }}">
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">Buscar</button>
    </div>

    <div class="form-group ml-3">
        <a class="btn btn-outline-success" id="downloadLink" onclick="exportF(this)">Descargar en excel</a>
    </div>
</form>


<div class="table-responsive">
    <table class="table table-sm table-bordered table-responsive small" id="tabla_positivos_por_fecha">
        <thead>
            <td>°</td>
            <th nowrap>id</th>
            <th nowrap>Run</th>
            <th nowrap>Nombre</th>
            <th nowrap>Sexo</th>
            <th nowrap>Edad</th>
            <th nowrap>Estado</th>
            <th nowrap>Epivigila</th>
            <th nowrap>Fecha Resultado</th>
            <th nowrap>Comuna</th>
            <th nowrap>Tipo Calle</th>
            <th nowrap>Dirección</th>
            <th nowrap>Número</th>
            <th nowrap>Departamento</th>
            <th nowrap>Teléfono</th>
            <th nowrap>Latitud</th>
            <th nowrap>Longitud</th>
        </thead>

        <tbody>
        @foreach($dataArray as $key => $suspectCase)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td nowrap>{{$suspectCase['ID']}}</td>
                    <td nowrap>{{$suspectCase['RUN']}}</td>
                    <td nowrap>{{$suspectCase['NAME']}}</td>
                    <td nowrap>{{$suspectCase['GENDER'] }}</td>
                    <td nowrap>{{$suspectCase['AGE'] }}</td>
                    <td nowrap>{{$suspectCase['STATE'] }}</td>
                    <td nowrap>{{$suspectCase['EPIVIGILA'] }}</td>
                    <td nowrap>{{$suspectCase['COVID19'] }}</td>
                    <td nowrap>{{$suspectCase['COMMUNE'] }}</td>
                    <td nowrap>{{$suspectCase['STREET_TYPE'] }}</td>
                    <td nowrap>{{$suspectCase['ADDRESS'] }}</td>
                    <td nowrap>{{$suspectCase['NUMBER'] }}</td>
                    <td nowrap>{{$suspectCase['DEPARTMENT'] }}</td>
                    <td nowrap>{{$suspectCase['TELEPHONE'] }}</td>
                    <td nowrap>{{$suspectCase['LATITUDE'] }}</td>
                    <td nowrap>{{$suspectCase['LONGITUDE'] }}</td>
                </tr>
        @endforeach
        </tbody>

    </table>
</div>
@endsection


@section('custom_js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
    function exportF(elem) {
        var table = document.getElementById("tabla_positivos_por_fecha");
        var html = table.outerHTML;
        var html_no_links = html.replace(/<a[^>]*>|<\/a>/g, ""); //remove if u want links in your table
        var url = 'data:application/vnd.ms-excel,' + escape(html_no_links); // Set your html table into url
        elem.setAttribute("href", url);
        elem.setAttribute("download", "positivos_por_fecha.xls"); // Choose the file name
        return false;
    }
</script>
@endsection
