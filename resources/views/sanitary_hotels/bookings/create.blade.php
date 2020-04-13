@extends('layouts.app')

@section('title', 'Crear Booking')

@section('content')

@include('sanitary_hotels.nav')

<h3 class="mb-3">Crear Booking</h3>

<form method="POST" class="form-horizontal" action="{{ route('sanitary_hotels.bookings.store') }}">
    @csrf
    @method('POST')

    <div class="form-row">
        <fieldset class="form-group col-4 col-md-4">
            <label for="for_patient_id">Paciente</label>
            <select name="patient_id" id="for_patient_id" class="form-control">
                @foreach($patients as $patient)
                <option value="{{ $patient->id }}">{{ $patient->fullName }}</option>
                @endforeach
            </select>
        </fieldset>

        <fieldset class="form-group col-4 col-md-4">
            <label for="for_room_id">Hotel - Habitación</label>
            <select name="room_id" id="for_room_id" class="form-control">
                @foreach($rooms as $room)
                <option value="{{ $room->id }}">{{ $room->hotel->name }} - Habitación {{ $room->number }}</option>
                @endforeach
            </select>
        </fieldset>
    </div>

    <div class="form-row">
        <fieldset class="form-group col-4 col-md-3">
            <label for="for_from">Desde</label>
            <input type="datetime-local" class="form-control date" name="from" id="for_from" required >
        </fieldset>

        <fieldset class="form-group col-4 col-md-3">
            <label for="for_to">Hasta (Estimado)</label>
            <input type="datetime-local" class="form-control date" name="to" id="for_to" required >
        </fieldset>


    </div>

    <fieldset class="form-group">
        <label for="for_indications">Indicaciones</label>
        <textarea class="form-control" id="for_indications" rows="3" name="indications"></textarea>
    </fieldset>

    <fieldset class="form-group" >
        <label for="for_observations">Observaciones</label>
        <textarea type="textarea" class="form-control" rows="3"  name="observations" id="for_observations"> </textarea>
    </fieldset>


    <button type="submit" class="btn btn-primary">Guardar</button>
    <a class="btn btn-outline-secondary" href="{{ route('sanitary_hotels.bookings.index') }}">Cancelar</a>


</form>

@endsection

@section('custom_js')

@endsection
