@extends('layouts.app')

@section('title', 'Crear Habitación')

@section('content')

@include('sanitary_residences.nav')

<h3 class="mb-3">Crear Habitación</h3>

<form method="POST" class="form-horizontal" action="{{ route('sanitary_residences.rooms.store') }}">
    @csrf
    @method('POST')

    <div class="form-row">
        <fieldset class="form-group col-4 col-md-2">
            <label for="for_number">Número de habitación</label>
            <input type="text" class="form-control" name="number" id="for_number"
                required placeholder="">
        </fieldset>

        <fieldset class="form-group col-4 col-md-2">
            <label for="for_floor">Número de Piso</label>
            <input type="text" class="form-control" name="floor" id="for_floor"
                required placeholder="">
        </fieldset>

        <fieldset class="form-group col-4 col-md-4">
            <label for="for_residence_id">Residence</label>
            <select name="residence_id" id="for_residence_id" class="form-control">
                @foreach($residences as $residence)
                    <option value="{{ $residence->id }}">{{ $residence->name }}</option>
                @endforeach
            </select>
        </fieldset>

    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>
    <a class="btn btn-outline-secondary" href="{{ route('sanitary_residences.rooms.index') }}">Cancelar</a>


</form>

@endsection

@section('custom_js')

@endsection
