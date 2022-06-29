@extends('layouts.app')

@section('title', 'Listado de usuarios')

@section('content')

@include('parameters.nav')

<h3 class="mb-3">Listado de usuarios</h3>

<form method="get" class="form-horizontal" action="{{ route('users.index') }}">

    <div class="form-row">
        @can('Admin')
        <div class="col-12 col-md-3 mb-3">
            <label for="" class="sr-only"></label>
            <a class="btn btn-primary" href="{{ route('users.create') }}">
                Crear usuario
            </a>
        </div>
        @endcan
        <div class="col-12 col-md-5 mb-3">
            <label for="" class="sr-only"></label>
            <input class="form-control" type="text" name="search" value="" placeholder="Nombre y/o apellido">
        </div>

        <div class="col-12 col-md-2 mb-3">
            <label for="" class="sr-only"></label>
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

<div class="table-responsive">
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Run</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Función</th>
                <th>Establecimientos</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td nowrap>{{ $user->run }}-{{$user->dv}}</td>
                <td nowrap>
                    <a href="{{ route('users.edit', $user) }}">{{ $user->name }}</a>
                    {{ $user->active == false ? '(x)':'' }}
                </td>
                <td nowrap>{{ $user->email }}</td>
                <td>{{ $user->function }}</td>
                <td class="small">
                    @if($user->establishments->count()<=3)
                    <ul>
                        @foreach($user->establishments as $establishment)
                        <li>{{ $establishment->alias }}</li>
                        @endforeach
                    </ul>
                    @else
                    <a href="#z{{$user->run}}" data-toggle="collapse" >Establecimientos {{$user->establishments->count()}} </a>
                    <!--<button type="button"   class="btn btn-link btn-sm" data-toggle="collapse" data-target="#estab">Cantidad de Establecimientos</button>-->
                    <div id="z{{$user->run}}" class="collapse">
                       <ul class="list-group">
                          @foreach($user->establishments as $establishment)
                          <li class="list-group-item">{{ $establishment->alias }}</li>
                          @endforeach
                       </ul>
                    </div>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection

@section('custom_js')

@endsection
