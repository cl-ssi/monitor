@extends('layouts.app')

@section('title', 'Seguimiento de Casos')

@section('content')

<h3 class="">Excel por Fecha de Toma de Muestra de mis Establecimientos</h3>
<form method="get" class="form-inline mb-3" action="{{ route('lab.suspect_cases.ownIndexFilter') }}">


    <a type="button" class="btn btn-success btn-sm mb-3" id="downloadLink" onclick="exportF(this)">Descargar en excel<i class="far fa-file-excel"></i></a>
    <!-- <a type="button" class="btn btn-success btn-sm mb-3" href="{{ route('lab.suspect_cases.ownIndexFilter', ['from' => $request->from, 'to' => $request->to] ) }}">Descargar <i class="far fa-file-excel"></i></a> -->
    <div class="form-group ml-3">
        <label for="for_from">Desde</label>
        <input type="datetime-local" class="form-control mx-sm-3" id="for_from" name="from" value="{{ Carbon\Carbon::parse($from)->format('Y-m-d\TH:i') }}">
    </div>
    <div class="form-group">
        <label for="for_to">Hasta</label>
        <input type="datetime-local" class="form-control mx-sm-3" id="for_to" name="to" value="{{ Carbon\Carbon::parse($to)->format('Y-m-d\TH:i') }}">
    </div>
    <select name="laboratory_id" id="for_laboratory" class="form-control" onchange="this.form.submit()">
        <option value="0">Todos los Laboratorios</option>
        @foreach ($laboratories as $key => $lab)
        <option value="{{$lab->id}}" {{($lab->id == $request->laboratory_id)?'selected':''}}>{{$lab->name}}</option>
        @endforeach
    </select>

    <div class="form-group">
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
    </div>
</form>

<table class="table table-sm table-bordered table-responsive small text-uppercase" id="tabla_mis_examenes">
    <thead>
        <th>#</th>
        <th>fecha_muestra</th>
        <th>origen</th>
        <th>nombre</th>
        <th>run</th>
        <th>edad</th>
        <th>sexo</th>
        <th>resultado_ifd</th>
        <th>pcr_sars_cov2</th>
        <th>sem</th>
        <th>epivigila</th>
        <th>fecha de resultado</th>
        <th>observación</th>
        <th>teléfono</th>
        <th>dirección</th>
        <th>comuna</th>
    </thead>
    <tbody>
        @foreach ($cases as $case)
        <tr>
            <td>{{ $case->id }}</td>
            <td>{{ $case->sample_at }}</td>
            <td>{{($case->establishment)?$case->establishment->alias.' - '.$case->origin: '',}}</td>
            <td>{{($case->patient)?$case->patient->fullName:''}}</td>
            <td>{{($case->patient)?$case->patient->Identifier:''}}</td>
            <td>{{$case->age}}</td>
            <td>{{strtoupper($case->gender[0])}}</td>
            <td>{{$case->result_ifd}}</td>
            <td>{{$case->Covid19}}</td>
            <td>{{$case->epidemiological_week}}</td>
            <td>{{$case->epivigila}}</td>
            <td>{{$case->pcr_sars_cov_2_at}}</td>
            <td>{{$case->observation}}</td>
            <td>{{($case->patient && $case->patient->demographic)?$case->patient->demographic->telephone:''}}</td>
            <td>{{($case->patient && $case->patient->demographic)?$case->patient->demographic->fullAddress:''}}</td>
            <td>{{($case->patient && $case->patient->demographic && $case->patient->demographic->commune)?$case->patient->demographic->commune->name:''}}</td>
        </tr>
        @endforeach
</table>

@endsection

@section('custom_js')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
    function exportF(elem) {
        let date = new Date()
        let day = date.getDate()
        let month = date.getMonth() + 1
        let year = date.getFullYear()
        let hour = date.getHours()
        let minute = date.getMinutes()
        var table = document.getElementById("tabla_mis_examenes");
        var html = table.outerHTML;
        var html_no_links = html.replace(/<a[^>]*>|<\/a>/g, ""); //remove if u want links in your table
        var url = 'data:application/vnd.ms-excel,' + escape(html_no_links); // Set your html table into url
        elem.setAttribute("href", url);
        elem.setAttribute("download", "mis_examenes_filtrados_descargados_el_" + day + "_" + month + "_" + year + "_" + hour + "_" + minute + ".xls"); // Choose the file name
        return false;
    }
</script>

@endsection

@section('custom_js_head')

@endsection