@extends('layouts.app')

@section('title', 'Listado de Hoteles')

@section('content')

@include('sanitary_hotels.nav')

<h3 class="mb-3">Listado de Hoteles</h3>

<a class="btn btn-primary mb-3" href="{{ route('sanitary_hotels.hotels.create') }}">Crear un nuevo hotel</a>

<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Teléfono</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($hotels as $hotel)
        <tr>
            <td>{{ $hotel->name }}</td>
            <td>{{ $hotel->address }}</td>
            <td>{{ $hotel->telephone }}</td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')

@endsection
