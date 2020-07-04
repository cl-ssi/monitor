@extends('layouts.app')

@section('title', 'Listado de usuarios')

@section('content')

@include('parameters.nav')

<h3 class="mb-3">Listado de usuarios</h3>

    <form method="get" action="{{ route('users.index') }}">
        <div class="form-group row">
            <div class="col-sm-2">
                @can('Admin')
                    <a class="btn btn-primary " href="{{ route('users.create') }}">
                        Crear usuario
                    </a>
                @endcan
            </div>
            <div class="col-sm-8">
                <input class="form-control" type="text" name="search" value="" placeholder="Nombre y/o apellido">
            </div>

            <div class="col-sm-2 ">
                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search"></i> Buscar</button>
            </div>

        </div>
    </form>


<h5>
        @if($search)
        <div class="alert alert-primary" role="alert">
            Los resultados para tu búsqueda "{{ $search }}" son:
        </div>
        @endif
</h5>


<table class="table">
    <thead>
        <tr>
            <th>Run</th>
            <th>Nombre</th>
            <th>Email</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->run }}-{{$user->dv}}</td>
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
