@extends('layouts.app')

@section('title', 'Listado de Habitaciones')

@section('content')

@include('sanitary_hotels.nav')

<h3 class="mb-3">Listado de Habitaciones</h3>
<a class="btn btn-primary mb-3" href="{{ route('sanitary_hotels.rooms.create') }}">Crear una nueva habitación</a>

<table class="table table-sm">
    <thead>
        <tr>
            <th>Hotel</th>
            <th>Piso</th>
            <th>Numero</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($rooms as $room)
        <tr>
            <td>{{ $room->hotel->name }}</td>
            <td>{{ $room->floor }}</td>
            <td>{{ $room->number }}</td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')

@endsection
