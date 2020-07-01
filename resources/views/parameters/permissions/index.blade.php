@extends('layouts.app')

@section('title', 'Permisos')

@section('content')

@include('parameters.nav')

<h3 class="mb-3">Permisos y roles</h3>

<a class="btn btn-primary mb-3" href="{{ route('parameters.permissions.create') }}">Crear</a>

<table class="table table-sm">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($permissions as $permission)
        <tr>
            <td nowrap>{{ $permission->id }}</td>
            <td nowrap>{{ $permission->name }}</td>
            <td>{{ $permission->description }}</td>
            <td>
                <a href="{{ route('parameters.permissions.edit', $permission->id )}}">
                <i class="fas fa-edit"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


@endsection

@section('custom_js')

@endsection
