@extends('layouts.app')

@section('title', 'Crear Habitación')

@section('content')
<h3 class="mb-3">Crear Habitación</h3>

<form method="POST" class="form-horizontal" action="{{ route('sanitary_hotels.rooms.store') }}">
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
            <label for="for_hotel_id">Hotel</label>
            <select name="hotel_id" id="for_hotel_id" class="form-control">
                @foreach($hotels as $hotel)
                    <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                @endforeach
            </select>
        </fieldset>

    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>


</form>

@endsection

@section('custom_js')

@endsection
