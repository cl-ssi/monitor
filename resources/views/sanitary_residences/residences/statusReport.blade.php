@extends('layouts.app')

@section('title', 'Status Report')

@section('content')

<h3 class="mb-3">Estado de residencias</h3>

<a class="btn btn-outline-success btn-sm mb-3" id="downloadLink" onclick="exportF(this)">Descargar en excel</a>

<div class="table-responsive">
    <table class="table table-sm table-bordered table-responsive text-center align-middle" id="tabla_estado_residencias">
        <thead>
            <th colspan="5">Ocupación</th>            
            <th colspan="3">Disponibilidad</th>
        </thead>

        <thead>
            <th nowrap>N°</th>
            <th nowrap>Residencia</th>
            <th nowrap>Habitaciones total</th>
            <th nowrap>Habitaciones ocupadas</th>
            <th nowrap>Pacientes en residencia</th>
            <th nowrap>Habitaciones disponibles</th>
            <th>Camas Single  en Habitaciones disponibles (Total de Camas Singles)</th>
            <th>Camas Dobles  en Habitaciones disponibles (Total de Camas Dobles</th>
        </thead>

        <tbody>
        @foreach($dataArray as $residencia)
            @if(!$loop->last)
            <tr>
                <td nowrap>{{$loop->iteration}}</td>
                <td nowrap>{{$residencia['residenceName']}}</td>
                <td nowrap>{{$residencia['totalRooms']}}</td>
                <td nowrap>{{$residencia['occupiedRooms']}}</td>
                <td nowrap>{{$residencia['patients'] }}</td>
                <td nowrap>{{$residencia['availableRooms'] }}</td>
                <td nowrap>{{$residencia['single'] }} ( {{$residencia['totalsinglebyresidence'] }} )</td>
                <td nowrap>{{$residencia['double'] }} ( {{$residencia['totaldoublebyresidence'] }} )</td>
            </tr>
            @else
            <tr>
                <th nowrap></th>
                <th nowrap>{{$residencia['residenceName']}}</th>
                <th nowrap>{{$residencia['totalRooms']}}</th>
                <th nowrap>{{$residencia['occupiedRooms']}}</th>
                <th nowrap>{{$residencia['patients'] }}</th>
                <th nowrap>{{$residencia['availableRooms'] }}</th>
                <th nowrap>{{$residencia['single'] }} ( {{$residencia['sumsingle'] }} )</th>
                <th nowrap>{{$residencia['double'] }} ( {{$residencia['sumdouble'] }} )</th>
            </tr>
            @endif
        @endforeach
        </tbody>

    </table>
</div>
@endsection


@section('custom_js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
let date = new Date()
let day = date.getDate()
let month = date.getMonth() + 1
let year = date.getFullYear()
let hour = date.getHours()
let minute = date.getMinutes()
    function exportF(elem) {
        var table = document.getElementById("tabla_estado_residencias");
        var html = table.outerHTML;
        var html_no_links = html.replace(/<a[^>]*>|<\/a>/g, ""); //remove if u want links in your table
        var url = 'data:application/vnd.ms-excel,' + escape(html_no_links); // Set your html table into url
        elem.setAttribute("href", url);
        elem.setAttribute("download", "residencia_sanitaria_consolidado_"+day+"_"+month+"_"+year+"_"+hour+"_"+minute+".xls"); // Choose the file name
        return false;
    }
</script>
@endsection
