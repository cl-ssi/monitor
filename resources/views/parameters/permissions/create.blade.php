@extends('layouts.app')

@section('title', 'Crear nuevo Permisos')

@section('content')

@include('parameters.nav')

<h3 class="mb-3">Crear nuevo Permisos</h3>

<form method="POST" class="form-horizontal" action="{{ route('parameters.permissions.store') }}">
    @csrf
    @method('POST')

    <div class="row">

        <fieldset class="form-group col">
            <label for="for_name">Nombre*</label>
            <input type="text" class="form-control" id="for_name"
                placeholder="nombre del permiso" name="name" autocomplete="off" required>
        </fieldset>

    </div>

    <div class="row">
        <fieldset class="form-group col">
            <label for="for_description">Descripción</label>
            <input type="text" class="form-control" id="for_name"
                placeholder="Descripción del Permiso" name="description" autocomplete="off">
        </fieldset>
    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>

</form>

@endsection

@section('custom_js')

@endsection
