@extends('layouts.app')

@section('title', 'Listado de Laboratorios')

@section('content')


<h3 class="mb-3">Listado de Laboratorios</h3>

<a class="btn btn-primary mb-3" href="{{ route('parameters.lab.create') }}">Crear nuevo laboratorio</a>

<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Externo</th>
            <th>Webservice Minsal</th>
            <th>Comuna</th>
            <th>Editar</th>
        </tr>
    </thead>
    <tbody>
    @foreach($laboratories as $laboratory)
        <tr>
            <td>{{ $laboratory->name }}</td>
            <td>
                @if ($laboratory->external == 1 )
                Sí
                @else
                No
                @endif
            </td>
            <td>
                @if ($laboratory->minsal_ws == 1 )
                Sí
                @else
                No
                @endif
            </td>
            <td>{{ $laboratory->commune->name }}</td>
            <td>
            <a href="{{ route('parameters.lab.edit', $laboratory) }}" class="btn btn-secondary float-left"><i class="fas fa-edit"></i></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')

@endsection
