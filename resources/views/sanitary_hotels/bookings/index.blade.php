@extends('layouts.app')

@section('title', 'Listado de Habitaciones')

@section('content')

@include('sanitary_hotels.nav')

<h3 class="mb-3">Listado de Bookings</h3>

<a class="btn btn-primary mb-3" href="{{ route('sanitary_hotels.bookings.create') }}">Crear un Booking</a>

@php ($piso = 0)

@foreach($rooms as $room)

    @if($room->floor != $piso)
        @if($piso != 0)
            </div>
        @endif
        <div class="row mt-3">
    @endif

    <div class="border text-center" style="width: 150px; height: 150px;">
        Habitación {{ $room->number }}
        <hr>

        @if($room->bookings->first())
        <a href="{{ route('sanitary_hotels.bookings.show',$room->bookings->first()->id) }}">
            {{ $room->bookings->first()->patient->fullName }}
        </a>

        @endif

    </div>

    @php ($piso = $room->floor)

@endforeach



<table class="table table-sm">
    <thead>
        <tr>
            <th>Paciente</th>
            <th>Hotel</th>
            <th>Habitación</th>
            <th>Desde</th>
            <th>Hasta (Estimado)</th>
            <th>Indicaciones</th>
            <th>Observaciones</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($bookings as $booking)
        <tr>
            <td>{{ $booking->patient->fullName }}</td>
            <td>{{ $booking->room->hotel->name }}</td>
            <td>{{ $booking->room->number }}</td>
            <td>{{ $booking->from }}</td>
            <td>{{ $booking->to }}</td>
            <td>{{ $booking->indications }}</td>
            <td>{{ $booking->observations }}</td>

            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')

@endsection
