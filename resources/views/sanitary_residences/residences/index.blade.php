@extends('layouts.app')

@section('title', 'Listado de Residencias')

@section('content')

@include('sanitary_residences.nav')

<h3 class="mb-3">Listado de Residencias</h3>

<a class="btn btn-primary mb-3" href="{{ route('sanitary_residences.residences.create') }}">Crear nueva residencia</a>

<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Teléfono</th>
            <th>Editar</th>
        </tr>
    </thead>
    <tbody>
        @foreach($residences as $residence)
        <tr>
            <td>{{ $residence->name }}</td>
            <td>{{ $residence->address }}</td>
            <td>{{ $residence->telephone }}</td>
            <td><a href="{{ route('sanitary_residences.residences.edit', $residence) }}" class="btn_edit"><i class="fas fa-edit"></i></a></td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')

@endsection
