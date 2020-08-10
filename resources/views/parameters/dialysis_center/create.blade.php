@extends('layouts.app')

@section('title', 'Crear tipo de solicitud')

@section('content')
@include('parameters.nav')


<h3 class="mb-3">Crear Centro de Dialisis</h3>

<form method="POST" class="form-horizontal" action="{{ route('parameters.dialysis_center.store') }}">
    @csrf
    @method('POST')

    <div class="form-row">
        <fieldset class="form-group col-6 col-md-4">
            <label for="for_name">Nombre</label>
            <input type="text" class="form-control" name="name" id="for_name" required placeholder="" autocomplete="off">
        </fieldset>
        
        <fieldset class="form-group col-6 col-md-4">
            <label for="for_commune_id">Comuna</label>
            <select class="form-control" name="commune_id" id="for_commune_id" required>
                <option value="">Seleccione Comuna</option>
                @foreach($communes as $commune)
                <option value="{{ $commune->id }}">{{ $commune->name }}</option>
                @endforeach
            </select>
        </fieldset>


    </div>


    <button type="submit" class="btn btn-primary">Guardar</button>


@endsection

@section('custom_js')

@endsection
