@extends('layouts.app')

@section('title', 'Listado de Centro de Dialisis')
@section('content')
@include('parameters.nav')

<h3 class="mb-3">Listado de Centro de Dialisis</h3>

<a class="btn btn-primary mb-3" href="{{ route('parameters.dialysis_center.create') }}">Crear un Centro de Dialisis </a>

<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Comuna</th>
            <th>Editar</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dialysiscenters as $dialysis_center)
            <tr>
                <td>{{ $dialysis_center->name }}</td>
                <td>
                {{ $dialysis_center->commune->name }}
                </td>
                <td>
                <a href="{{ route('parameters.dialysis_center.edit', $dialysis_center) }}" class="btn btn-secondary float-left"><i class="fas fa-edit"></i></a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')

@endsection
