@extends('layouts.app')

@section('title', 'Listado de Estados')

@section('content')
@include('parameters.nav')

<h3 class="mb-3">Listado de Estados</h3>

<a class="btn btn-primary mb-3" href="{{ route('parameters.statu.create') }}">Crear Nuevo Estado</a>

<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>            
            <th>Editar</th>
        </tr>
    </thead>
    <tbody>
        @foreach($status as $statu)
            <tr>
                <td>{{ $statu->name }}</td>            
                <td>
                <a href="{{ route('parameters.statu.edit', $statu) }}" class="btn btn-secondary float-left"><i class="fas fa-edit"></i></a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')

@endsection
