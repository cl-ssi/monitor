@extends('layouts.app')

@section('title', 'Excel Signos Vitales')

@section('content')

@include('sanitary_residences.nav')

<a class="btn btn-outline-success btn-sm mb-3" id="downloadLink" onclick="exportF(this)">Descargar en excel</a>

<table class="table table-sm table-bordered table-responsive small" id="tabla_booking_vitalsign">
    <thead>
        <th nowrap>Paciente</th>
        <th nowrap>Fecha y Hora</th>
        <th nowrap>Temperatura</th>
        <th nowrap>Frecuencia Cardiaca</th>
        <th nowrap>Presión Arterial</th>
        <th nowrap>Sat O2</th>
        <th nowrap>HGT</th>
        <th nowrap>Escala Dolor</th>
        <th nowrap>Observaciones</th>
        <th nowrap>Funcionario</th>
        <th nowrap>Residencia Sanitaria</th>
    </thead>
    <tbody>
        @foreach(Auth::user()->residences as $residence)        
        @foreach($vitalsigns as $vitalsign)
        @if($residence->id == $vitalsign->booking->room->residence->id)
        <tr>
        <td nowrap>{{ $vitalsign->patient->fullname}}</td>
        <td nowrap>{{ $vitalsign->created_at}}</td>
        <td nowrap>{{ $vitalsign->temperature}}</td>
        <td nowrap>{{ $vitalsign->heart_rate}}</td>
        <td nowrap>{{ $vitalsign->blood_pressure}}</td>
        <td nowrap>{{ $vitalsign->oxygen_saturation}}</td>
        <td nowrap>{{ $vitalsign->hgt}}</td>
        <td nowrap>{{ $vitalsign->pain_scale}}</td>
        <td >{{ $vitalsign->observations}}</td>
        <td nowrap>{{ $vitalsign->user->name }}</td>
        <td nowrap>{{ $vitalsign->booking->room->residence->name }}</td>
        </tr>
        @endif
        @endforeach
        
        @endforeach
    </tbody>

</table>


@endsection

@section('custom_js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
    function exportF(elem) {
        var table = document.getElementById("tabla_booking_vitalsign");
        var html = table.outerHTML;
        var html_no_links = html.replace(/<a[^>]*>|<\/a>/g, ""); //remove if u want links in your table
        var url = 'data:application/vnd.ms-excel,' + escape(html_no_links); // Set your html table into url
        elem.setAttribute("href", url);
        elem.setAttribute("download", "signos_vitales.xls"); // Choose the file name
        return false;
    }
</script>
@endsection