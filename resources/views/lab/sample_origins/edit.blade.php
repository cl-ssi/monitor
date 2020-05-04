@extends('layouts.app')

@section('title', 'Editar Origen')

@section('content')
<h3 class="mb-3">Editar Origen</h3>

<form method="POST" class="form-horizontal" action="{{ route('lab.sample_origins.update', $sampleOrigin) }}">
    @csrf
    @method('PUT')

    <div class="form-row">
        <fieldset class="form-group col">
            <label for="for_name">Nombre del origen</label>
            <input type="text" class="form-control" name="name" id="for_name"
                required value="{{ $sampleOrigin->name }}">
        </fieldset>

        <fieldset class="form-group col">
            <label for="for_address">Dirección (opcional)</label>
            <input type="text" class="form-control" name="address" id="for_address"
                value="{{ $sampleOrigin->address }}">
        </fieldset>

    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>
    
</form>

@endsection

@section('custom_js')

@endsection
