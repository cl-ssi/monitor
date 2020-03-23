@extends('layouts.app')

@section('title', 'Listado de usuarios')

@section('content')

@include('parameters.nav')

<h3 class="mb-3">Listado de usuarios</h3>

@can('Admin')
<div class="col-4 col-sm-3">
    <a class="btn btn-primary mb-4" href="{{ route('users.create') }}">
        Crear usuario
    </a>
</div>
@endcan

<table class="table">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td> <a href="{{ route('users.edit', $user) }}">Editar</a> </td>
        </tr>
        @endforeach
    </tbody>
</table>


@endsection

@section('custom_js')

@endsection
