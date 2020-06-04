@extends('layouts.app')

@section('title', 'Listado de Inmunoglobulinas IgG IgM')
@section('content')

<h3 class="mb-3">Listado Test de Inmunoglobulinas IgG IgM</h3>

<div class="col-4 col-md-2">
    <a class="btn btn-primary mb-3" href="{{ route('lab.inmuno_tests.create', 'search_false') }}">
        Ingresar Nuevo Test
    </a>
</div>

<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Run o (ID)</th>
            <th>Nombre Completo</th>
            <th>Dirección</th>
            <th>Comuna</th>
            <th>Fono</th>
            <th>Entregado Por</th>
            <th>Entregado el</th>
            <th>Editar</th>
            <th>Eliminar</th>
        </tr>
    </thead>

    <tbody>

    </tbody>
</table>

@endsection

@section('custom_js')

@endsection
