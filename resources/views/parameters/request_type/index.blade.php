@extends('layouts.app')

@section('title', 'Tipos de eventos')

@section('content')
@include('parameters.nav')

<h3 class="mb-3">Tipos de Solicitudes</h3>

<a class="btn btn-primary mb-3" href="{{ route('parameters.request_type.create') }}"><i class="fas fa-plus"></i> Crear tipo de solicitud</a>

<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Editar</th>
        </tr>
    </thead>
    <tbody>
        @foreach($request_types as $request)
            <tr>
                <td>{{ $request->name }}</td>
                <td>
                <a href="{{ route('parameters.request_type.edit', $request) }}" class="btn btn-secondary float-left"><i class="fas fa-edit"></i></a>

                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')

@endsection
