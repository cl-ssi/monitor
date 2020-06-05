@extends('layouts.app')

@section('title', 'Status Report')

@section('content')

<h3 class="mb-3">Estado de residencias</h3>

@can('SanitaryResidence: admin')
<a class="btn btn-outline-success btn-sm mb-3" id="downloadLink" onclick="exportF(this)">Descargar en excel</a>
@endcan

<div class="table-responsive">
    <table class="table table-sm table-bordered table-responsive small" id="tabla_estado_residencias">
        <thead>
            <th nowrap>Residencia</th>
            <th nowrap>Habitaciones total</th>
            <th nowrap>Habitaciones ocupadas</th>
            <th nowrap>Pacientes en residencia</th>
            <th nowrap>Habitaciones disponibles</th>
        </thead>

        <tbody>
        @foreach($dataArray as $residencia)
            @if(!$loop->last)
            <tr>
                <td nowrap>{{$residencia['residenceName']}}</td>
                <td nowrap>{{$residencia['totalRooms']}}</td>
                <td nowrap>{{$residencia['occupiedRooms']}}</td>
                <td nowrap>{{$residencia['patients'] }}</td>
                <td nowrap>{{$residencia['availableRooms'] }}</td>
            </tr>
            @else
            <tr>
                <th nowrap>{{$residencia['residenceName']}}</th>
                <th nowrap>{{$residencia['totalRooms']}}</th>
                <th nowrap>{{$residencia['occupiedRooms']}}</th>
                <th nowrap>{{$residencia['patients'] }}</th>
                <th nowrap>{{$residencia['availableRooms'] }}</th>
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
    function exportF(elem) {
        var table = document.getElementById("tabla_estado_residencias");
        var html = table.outerHTML;
        var html_no_links = html.replace(/<a[^>]*>|<\/a>/g, ""); //remove if u want links in your table
        var url = 'data:application/vnd.ms-excel,' + escape(html_no_links); // Set your html table into url
        elem.setAttribute("href", url);
        elem.setAttribute("download", "residencia_sanitaria.xls"); // Choose the file name
        return false;
    }
</script>
@endsection

