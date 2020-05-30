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

@endsection

@section('custom_js')

@endsection
