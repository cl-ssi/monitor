@extends('layouts.app')

@section('title', 'Crear tipo de solicitud')

@section('content')
@include('parameters.nav')


<h3 class="mb-3">Crear tipo de solicitud</h3>

<form method="POST" class="form-horizontal" action="{{ route('parameters.request_type.store') }}">
    @csrf
    @method('POST')
    <div class="form-row">

        <fieldset class="form-group col-12 col-md-4">
            <label for="for_name">Nombre</label>
            <input type="text" class="form-control" name="name" id="for_name" required placeholder="" autocomplete="off">
        </fieldset>

    </div>


    <button type="submit" class="btn btn-primary">Guardar</button>

    <a class="btn btn-outline-secondary" href="{{ route('parameters.request_type') }}">Cancelar</a>

</form>


@endsection

@section('custom_js')

@endsection
