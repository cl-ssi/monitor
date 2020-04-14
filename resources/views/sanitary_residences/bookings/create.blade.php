@extends('layouts.app')

@section('title', 'Crear Booking')

@section('content')

@include('sanitary_residences.nav')

<h3 class="mb-3">Crear Booking</h3>

<form method="POST" class="form-horizontal" action="{{ route('sanitary_residences.bookings.store') }}">
    @csrf
    @method('POST')

    <div class="form-row">

        <fieldset class="form-group col-12 col-md-6">
            <label for="for_patient_id">Paciente</label>
            <select name="patient_id" id="for_patient_id" class="form-control">
                @foreach($patients as $patient)
                <option value="{{ $patient->id }}">{{ $patient->fullName }}</option>
                @endforeach
            </select>
        </fieldset>

        <fieldset class="form-group col-12 col-md-4">
            <label for="for_room_id">Residencia - Habitación</label>
            <select name="room_id" id="for_room_id" class="form-control">
                @foreach($rooms as $room)
                <option value="{{ $room->id }}">{{ $room->residence->name }} - Habitación {{ $room->number }}</option>
                @endforeach
            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_prevision">Previsión</label>
            <select name="prevision" id="for_prevision" class="form-control">
                <option value="Sin Previsión">Sin Previsión</option>
                <option value="Fonasa A">Fonasa A</option>
                <option value="Fonasa B">Fonasa B</option>
                <option value="Fonasa C">Fonasa C</option>
                <option value="Fonasa D">Fonasa D</option>
                <option value="Fonasa E">Fonasa E</option>
                <option value="ISAPRE">ISAPRE</option>
            </select>
        </fieldset>

    </div>

    <div class="form-row">

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_from">Desde</label>
            <input type="datetime-local" class="form-control date" name="from" id="for_from" required>
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_to">Hasta (Estimado)</label>
            <input type="datetime-local" class="form-control date" name="to" id="for_to" required>
        </fieldset>

        <fieldset class="form-group col-5 col-md-2">
            <label for="for_length_of_stay">Estadía (Días)</label>
            <input type="number" class="form-control" name="length_of_stay" id="for_length_of_stay">
        </fieldset>

        <fieldset class="form-group col-7 col-md-4">
            <label for="for_entry_criteria">Criterio de Ingreso</label>
            <input type="text" class="form-control" name="entry_criteria" id="for_entry_criteria">
        </fieldset>

    </div>


    <div class="form-row">

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_responsible_family_member">Familiar Responsable</label>
            <input type="text" class="form-control" name="responsible_family_member" id="for_responsible_family_member" autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_relationship">Parentesco</label>
            <input type="text" class="form-control" name="relationship" id="for_relationship">
        </fieldset>

        <fieldset class="form-group col-6 col-md-3">
            <label for="for_onset_on_symptoms">Fecha de Inicio de Sintomas</label>
            <input type="date" class="form-control" name="onset_on_symptoms" id="for_onset_on_symptoms">
        </fieldset>

        <fieldset class="form-group col-6 col-md-3">
            <label for="for_end_of_symptoms">Fecha de Termino de Sintomas</label>
            <input type="date" class="form-control" name="end_of_symptoms" id="for_end_of_symptoms" >
        </fieldset>

    </div>

    <div class="form-row">

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_doctor">Doctor</label>
            <input type="text" class="form-control" name="doctor" id="for_doctor">
        </fieldset>

        <fieldset class="form-group col-12 col-md-9">
            <label for="for_diagnostic">Diagnostico</label>
            <input type="text" class="form-control" name="diagnostic" id="for_diagnostic">
        </fieldset>

    </div>

    <div class="form-row">

        <fieldset class="form-group col-12 col-md-6">
            <label for="for_allergies">Alergias</label>
            <input type="text" class="form-control" name="allergies" id="for_allergies" autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-12 col-md-6">
            <label for="for_commonly_used_drugs">Farmacos de Uso Común</label>
            <input type="text" class="form-control" name="commonly_used_drugs" id="for_commonly_used_drugs" autocomplete="off">
        </fieldset>
    </div>


    <div class="form-row">
        <fieldset class="form-group col-12">
            <label for="for_morbid_history">Antecedentes Mórbidos</label>
            <textarea class="form-control" id="for_morbid_history" rows="2" name="morbid_history"></textarea>
        </fieldset>
    </div>

    <div class="form-row">
        <fieldset class="form-group col-12 col-md-7">
            <label for="for_indications">Indicaciones</label>
            <textarea class="form-control" id="for_indications" rows="6" name="indications"></textarea>
        </fieldset>

        <fieldset class="form-group col-12 col-md-5">
            <label for="for_observations">Observaciones</label>
            <textarea type="textarea" class="form-control" rows="4" name="observations" id="for_observations"> </textarea>
        </fieldset>
    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>
    <a class="btn btn-outline-secondary" href="{{ route('sanitary_residences.bookings.index') }}">Cancelar</a>


</form>

@endsection

@section('custom_js')

@endsection
