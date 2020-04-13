@extends('layouts.app')

@section('title', 'Listado de Signos Vitales')

@section('content')

@include('sanitary_hotels.nav')

<h3 class="mb-3">Listado de Signos Vitales</h3>
<a class="btn btn-primary mb-3" href="{{ route('sanitary_hotels.vital_signs.create') }}">Añadir un Signo Vital a Paciente</a>

<table class="table table-sm">
    <thead>
        <tr>
            <th>Paciente</th>
            <th>Chequeado por</th>
            <th>Temperatura</th>
            <th>Frec. Cardiaca</th>
            <th>Presión Arterial</th>
            <th>Frec. Respiratoria</th>
            <th>SAT 02</th>
            <th>HGT</th>
            <th>Escala Dolor</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($vitalsigns as $vitalsign)
        <tr>
            <td>{{ $vitalsign->patient->fullName }}</td>
            <td>{{ $vitalsign->user->name }}</td>
            <td>{{ $vitalsign->temperature }}</td>
            <td>{{ $vitalsign->heart_rate }}</td>
            <td>{{ $vitalsign->blood_pressure }}</td>
            <td>{{ $vitalsign->respiratory_rate }}</td>
            <td>{{ $vitalsign->oxygen_saturation }}</td>
            <td>{{ $vitalsign->hgt }}</td>
            <td>{{ $vitalsign->pain_scale }}</td>
            <td></td>
        </tr>
        @endforeach

    </tbody>
</table>

@endsection

@section('custom_js')

@endsection
