@extends('layouts.app')

@section('title', 'Listado de Habitaciones')

@section('content')

@include('sanitary_hotels.nav')

<h3 class="mb-3">Listado de Bookings</h3>

<a class="btn btn-primary mb-3" href="{{ route('sanitary_hotels.bookings.create') }}">Crear un Booking</a>

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
