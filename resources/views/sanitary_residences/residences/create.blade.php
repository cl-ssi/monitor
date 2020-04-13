@extends('layouts.app')

@section('title', 'Crear Residencia')

@section('content')

@include('sanitary_residences.nav')

<h3 class="mb-3">Crear Residencia</h3>

<form method="POST" class="form-horizontal" action="{{ route('sanitary_residences.residences.store') }}">
    @csrf
    @method('POST')

    <div class="form-row">
        <fieldset class="form-group col-4">
            <label for="for_name">Nombre</label>
            <input type="text" class="form-control" name="name" id="for_name"
                required placeholder="">
        </fieldset>

        <fieldset class="form-group col">
            <label for="for_address">Dirección</label>
            <input type="text" class="form-control" name="address" id="for_address"
                required placeholder="">
        </fieldset>

        <fieldset class="form-group col">
            <label for="for_telphone">Teléfono</label>
            <input type="text" class="form-control" name="telphone" id="for_telphone"
                required placeholder="">
        </fieldset>

    </div>


    <button type="submit" class="btn btn-primary">Guardar</button>

    <a class="btn btn-outline-secondary" href="{{ route('sanitary_residences.residences.index') }}">Cancelar</a>

</form>


@endsection

@section('custom_js')

@endsection
