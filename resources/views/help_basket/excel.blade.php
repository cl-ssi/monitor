@extends('layouts.app')

@section('title', 'Listado de Entregas de Canastas')
@section('content')
@include('help_basket.nav')

<h3 class="mb-3">Listado de Canasta Entregadas</h3>
    @foreach($helpbaskets as $helpBasket)
    <h3> {{ $helpBasket->user->name }} {{ $helpBasket->where('user_id',$helpBasket->user_id)->count() }} </h3>
    @endforeach

    <a class="btn btn-outline-success btn-sm mb-3" id="downloadLink" onclick="exportF(this)">Descargar en excel</a>


<table class="table table-sm table-bordered" id="tabla_basket">
    <thead>
        <tr>
            <th nowrap>Run o (ID)</th>
            <th nowrap>Nombre Completo</th>
            <th nowrap>Dirección</th>
            <th nowrap>Comuna</th>
            <th nowrap>Entregado Por</th>
            <th nowrap>Entregado el</th>
            <th nowrap>Observaciones</th>
        </tr>
    </thead>

    <tbody>
        @foreach($helpbaskets as $helpBasket)
        <tr>
            <td nowrap>{{$helpBasket->identifier}}</td>
            <td nowrap>{{$helpBasket->fullName}}</td>
            <td nowrap>{{$helpBasket->address}} {{$helpBasket->number}} {{$helpBasket->department}} </td>
            <td nowrap>{{$helpBasket->commune->name}}</td>
            <td nowrap>{{$helpBasket->user->name}}</td>
            <td nowrap>{{$helpBasket->updated_at->format('d-m-Y H:i')}}</td>
            <td nowrap>{{$helpBasket->observations}}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
    function exportF(elem) {
        var table = document.getElementById("tabla_basket");
        var html = table.outerHTML;
        var html_no_links = html.replace(/<a[^>]*>|<\/a>/g, ""); //remove if u want links in your table
        var url = 'data:application/vnd.ms-excel,' + escape(html_no_links); // Set your html table into url
        elem.setAttribute("href", url);
        elem.setAttribute("download", "resporte_canastas.xls"); // Choose the file name
        return false;
    }
</script>

@endsection