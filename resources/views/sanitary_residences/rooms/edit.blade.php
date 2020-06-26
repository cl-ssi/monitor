@extends('layouts.app')

@section('title', 'Editar Habitación')

@section('content')

@include('sanitary_residences.nav')

<h3 class="mb-3">Editar Habitación</h3>

<form method="POST" class="form-horizontal" action="{{ route('sanitary_residences.rooms.update', $room) }}">
    @csrf
    @method('PUT')

    <div class="form-row">
        <fieldset class="form-group col-4 col-md-2">
            <label for="for_number">Número de habitación</label>
            <input type="text" class="form-control" name="number" id="for_number"
                required placeholder=""
                value ="{{$room->number}}"
                >
        </fieldset>

        <fieldset class="form-group col-4 col-md-2">
            <label for="for_floor">Número de Piso</label>
            <input type="text" class="form-control" name="floor" id="for_floor"
                required placeholder=""
                value ="{{$room->floor}}"
                >
        </fieldset>

        <fieldset class="form-group col-4 col-md-4">
            <label for="for_residence_id">Residencia</label>
            <select name="residence_id" id="for_residence_id" class="form-control">
                @foreach($residences as $residence)
                    <option value="{{ $residence->id }}" {{($room->residence_id == $residence->id) ? 'selected': ''}}>{{ $residence->name }}</option>
                @endforeach
            </select>
        </fieldset>
    </div>

    <div class="form-row">
        <fieldset class="form-group col-4 col-md-2">
            <label for="for_single">Camas Singles</label>
            <input type="number" class="form-control" name="single" id="for_single"
                 placeholder="" value ="{{$room->single}}">
        </fieldset>

        <fieldset class="form-group col-4 col-md-2">
            <label for="for_double">Camas Dobles</label>
            <input type="number" class="form-control" name="double" id="for_double"
                 placeholder="" value ="{{$room->double}}">
        </fieldset>
    </div>

    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a class="btn btn-outline-secondary" href="{{ route('sanitary_residences.rooms.index') }}">Cancelar</a>


</form>

@endsection

@section('custom_js')

@endsection
