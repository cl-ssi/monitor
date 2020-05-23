@extends('layouts.app')

@section('title', 'Crear Residencia')

@section('content')

@include('sanitary_residences.nav')

<h3 class="mb-3">Editar Residencia</h3>

<form method="POST" class="form-horizontal" action="{{ route('sanitary_residences.residences.update', $residence) }}">
    @csrf
    @method('PUT')

    <div class="form-row">
        <fieldset class="form-group col-4">
            <label for="for_name">Nombre</label>
            <input type="text" class="form-control" name="name" id="for_name"
                required placeholder="" autocomplete="off" value ="{{$residence->name}}">
        </fieldset>

        <fieldset class="form-group col">
            <label for="for_address">Dirección</label>
            <input type="text" class="form-control" name="address" id="for_address"
                required placeholder="" autocomplete="off" value ="{{$residence->address}}">
        </fieldset>

        <fieldset class="form-group col">
            <label for="for_telphone">Teléfono</label>
            <input type="number" class="form-control" name="telephone" id="for_telephone"
                 placeholder="" value ="{{$residence->telephone}}">
        </fieldset>

    </div>

    <div class="form-row">
        <fieldset class="form-group col-2">
            <label for="for_width">Ancho (en Pixeles, ej:172)*</label>
            <input type="number" class="form-control" name="width" id="for_width"
                required value ="{{$residence->width}}">
        </fieldset>

        <fieldset class="form-group col-2">
            <label for="for_height">Largo (en Pixeles, ej:172)*</label>
            <input type="number" class="form-control" name="height" id="for_height"
                required value ="{{$residence->height}}">
        </fieldset>
    </div>


    <button type="submit" class="btn btn-primary">Guardar</button>

    <a class="btn btn-outline-secondary" href="{{ route('sanitary_residences.residences.index') }}">Cancelar</a>

</form>

@endsection

@section('custom_js')

@endsection