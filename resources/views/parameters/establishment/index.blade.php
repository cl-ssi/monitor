@extends('layouts.app')

@section('title', 'Listado de Establecimientos')

@section('content')
@include('parameters.nav')


<h3 class="mb-3">Listado de Establecimientos</h3>

<a class="btn btn-primary mb-3" href="{{ route('parameters.establishment.create')}}">Crear nuevo establecimiento</a>

<div class="table-responsive">
<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Alias</th>            
            <th>Nuevo Código DEIS</th>            
            <th>Dirección</th>
            <th>Telefono</th>
            <th>Email</th>
            <th>Editar</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($establishments as $establishment)

        <tr>
            <td>{{$establishment->name}}</td>
            <td>{{$establishment->alias}}</td>
            <td>{{$establishment->new_code_deis}}</td>            
            <td>{{$establishment->address}}</td>
            <td>{{$establishment->telephone}}</td>
            <td>{{$establishment->email}}</td>
            <td>
                <a href="{{route('parameters.establishment.edit', $establishment)}}">
                <i class="fas fa-edit"></i>
                </a>
            </td>
        </tr>
    @endforeach
        
    </tbody>
</table>
</div>



@endsection

@section('custom_js')

@endsection