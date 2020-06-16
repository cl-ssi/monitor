@extends('layouts.app')

@section('title', 'Tipos de eventos')

@section('content')
@include('parameters.nav')

<h3 class="mb-3">Tipos de eventos</h3>

<a class="btn btn-primary mb-3" href="{{ route('parameters.EventType.create') }}">Crear tipo de evento</a>

<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>            
            <th>Editar</th>
        </tr>
    </thead>
    <tbody>
        @foreach($event_types as $eventos)
            <tr>
                <td>{{ $eventos->name }}</td>            
                <td>
                <a href="{{ route('parameters.EventType.edit', $eventos) }}" class="btn btn-secondary float-left"><i class="fas fa-edit"></i></a>
                
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')

@endsection
