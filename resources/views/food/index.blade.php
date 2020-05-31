@extends('layouts.app')

@section('title', 'Listado de Entregas de Canastas')
@section('content')
@include('food.nav')

<h3 class="mb-3">Listado de Canasta Entregadas</h3>

<div class="col-4 col-md-2">
    <a class="btn btn-primary mb-3" href="{{ route('food.create') }}">
        Entregar Canasta
    </a>
</div>

<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Run o (ID)</th>
            <th>Nombre Completo</th>
            <th>Dirección</th>
            <th>Comuna</th>
            <th>Editar</th>
        </tr>
    </thead>

    <tbody>
        @foreach($foodbaskets as $foodbasket)
        <tr>
            <td>{{$foodbasket->identifier}}</td>
            <td>{{$foodbasket->fullName}}</td>
            <td>{{$foodbasket->address}}</td>
            <td>{{$foodbasket->commune->name}}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')

@endsection