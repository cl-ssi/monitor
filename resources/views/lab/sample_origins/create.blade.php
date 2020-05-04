@extends('layouts.app')

@section('title', 'Crear nuevo origen')

@section('content')
<h3 class="mb-3">Crear nuevo origen</h3>

<form method="POST" class="form-horizontal" action="{{ route('lab.sample_origins.store') }}">
    @csrf
    @method('POST')

    <div class="form-row">
        <fieldset class="form-group col">
            <label for="for_name">Nombre del Origen</label>
            <input type="text" class="form-control" name="name" id="for_name"
                required>
        </fieldset>

        <fieldset class="form-group col">
            <label for="for_address">Dirección (opcional)</label>
            <input type="text" class="form-control" name="address" id="for_address">
        </fieldset>

    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>


</form>

@endsection

@section('custom_js')

@endsection
