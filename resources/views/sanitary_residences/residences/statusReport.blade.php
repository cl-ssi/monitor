@extends('layouts.app')

@section('title', 'Status Report')

@section('content')

@include('sanitary_residences.nav')

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

@endsection

