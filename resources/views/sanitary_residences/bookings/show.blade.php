@extends('layouts.app')

@section('title', 'Ver booking')

@section('content')

@include('sanitary_residences.nav')


<u>
    <strong>
        <h3 class="mb-3" align="center">{{ $booking->patient->fullName }}</h3>
        <h4 class="mb-3" align="center">{{ $booking->room->residence->name }}</h4>
        <h5 class="mb-3" align="center">Habitacion:{{ $booking->room->number }}</h5>
    </strong>
</u>

<hr>
<h5 class="mb-3" align="center">Ingreso:{{ $booking->from->format('d-m-Y H:i') }} - Salida Estimada:{{ $booking->to->format('d-m-Y H:i') }}</h5>
<hr>

@if ($booking->indications <> null)
    
    <label for="for_indications">Indicaciones</label>
    <p readonly class="form-control" id="for_indications" rows="3" name="indications">{{ $booking->indications }}</p>
    <hr>
    @endif

    @if ($booking->observations <> null)
        <label for="for_observations">Observaciones</label>
        <p readonly  class="form-control" rows="3" name="observations" id="for_observations">{{ $booking->observations }}</p>
        <hr>
        @endif

        






        

        @include('sanitary_residences.vital_signs.partials.create', compact('booking'))

        <hr>

        @include('sanitary_residences.vital_signs.partials.index', compact('booking'))

        @endsection

        @section('custom_js')

        @endsection