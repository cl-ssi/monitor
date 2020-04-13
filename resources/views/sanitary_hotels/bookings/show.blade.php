@extends('layouts.app')

@section('title', 'Ver booking')

@section('content')

@include('sanitary_hotels.nav')

<h3 class="mb-3">Ver booking</h3>

{{ $booking->id }}

// Agregar datos del booking

<hr>

@include('sanitary_hotels.vital_signs.partials.create', compact('booking'))

<hr>

@include('sanitary_hotels.vital_signs.partials.index', compact('booking'))

@endsection

@section('custom_js')

@endsection
