@extends('layouts.app')

@section('title', 'Crear Booking')

@section('content')

@include('sanitary_residences.nav')

<h3 class="mb-3">Crear Booking Para Paciente {{ $patient->fullName }} </h3>

<form method="POST" class="form-horizontal" action="{{ route('sanitary_residences.bookings.store') }}">
    @csrf
    @method('POST')

    <div class="form-row">

        <fieldset class="form-group col-12 col-md-6">
            <label for="for_patient_id">Paciente*</label>
            <input type="hidden" id="patient_id" name="patient_id" value="{{$patient->id}}">
            <input type="text" class="form-control" name="patient_name" id="for_patient_name" autocomplete="off" value="{{ $patient->fullName }}" readonly="true">
        </fieldset>

        <fieldset class="form-group col-12 col-md-4">
            <label for="for_room_id">Residencia - Habitación - Cuartos (Single: Doble:)*</label>
            <select name="room_id" id="for_room_id" class="form-control">
                @foreach(Auth::user()->residences as $residence)
                    @foreach($residence->rooms->sortBy('number') as $room)
                    <option value="{{ $room->id }}">{{ $room->residence->name }} - Habitación {{ $room->number }} - S:{{ $room->single }} D:{{ $room->double }}</option>
                    @endforeach
                @endforeach
            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_prevision">Previsión*</label>
            <select name="prevision" id="for_prevision" class="form-control" required>
                <option value="">Seleccionar Previsión</option>
                <option value="Sin Previsión">Sin Previsión</option>
                <option value="Fonasa A">Fonasa A</option>
                <option value="Fonasa B">Fonasa B</option>
                <option value="Fonasa C">Fonasa C</option>
                <option value="Fonasa D">Fonasa D</option>
                <option value="Fonasa E">Fonasa E</option>
                <option value="ISAPRE">ISAPRE</option>
                <option value="OTRO">OTRO</option>
            </select>
        </fieldset>

    </div>

    <div class="form-row">

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_from">Desde*</label>
            <input type="datetime-local" class="form-control date" name="from" id="for_from" required>
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_to">Hasta (Estimado)</label>
            <input type="datetime-local" class="form-control date" name="to" id="for_to" >
        </fieldset>

        <fieldset class="form-group col-5 col-md-2">
            <label for="for_length_of_stay">Estadía (Días)</label>
            <input type="number" class="form-control" name="length_of_stay" id="for_length_of_stay">
        </fieldset>

        <!-- <fieldset class="form-group col-7 col-md-4">
            <label for="for_entry_criteria">Criterio de Ingreso</label>
            <input type="text" class="form-control" name="entry_criteria" id="for_entry_criteria">
        </fieldset> -->
        <fieldset class="form-group col-7 col-md-4">
            <label for="for_prevision">Criterio de Ingreso*</label>
            <select name="entry_criteria" id="for_entry_criteria" class="form-control" required>
                <option value="">Seleccione Condición</option>
                <option value="PCR +">PCR +</option>
                <option value="Otro">Otro</option>
                <option value="Contacto Estrecho">Contacto Estrecho</option>
                <option value="Sospecha">Sospecha</option>
                <option value="Probable">Probable</option>
                <option value="Viajero">Viajero</option>
            </select>
        </fieldset>

    </div>


    <div class="form-row">

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_responsible_family_member">Familiar Responsable/Telefono</label>
            <input type="text" class="form-control" name="responsible_family_member" id="for_responsible_family_member" autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_relationship">Parentesco</label>
            <input type="text" class="form-control" name="relationship" id="for_relationship">
        </fieldset>

        <fieldset class="form-group col-6 col-md-3">
            <label for="for_onset_on_symptoms">Fecha de Inicio de Sintomas (Epivigila)</label>
            @if($request->input('symptoms_epivigila'))
            <input type="date" class="form-control" name="onset_on_symptoms" id="for_onset_on_symptoms" value="{{ $request->input('symptoms_epivigila') }}" readonly>
            @else
            <input type="date" class="form-control" name="onset_on_symptoms" id="for_onset_on_symptoms">
            @endif
            
            
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
            <textarea class="form-control" id="for_morbid_history" rows="2" name="morbid_history">@if($request->input('morbid_history')){{$request->input('morbid_history')}}@endif
            
            </textarea>
        </fieldset>
    </div>

    <div class="form-row">
        <fieldset class="form-group col-12 col-md-7">
            <label for="for_indications">Indicaciones</label>
            <textarea class="form-control" id="for_indications" rows="6" name="indications"></textarea>
        </fieldset>

        <fieldset class="form-group col-12 col-md-5">
            <label for="for_observations">Observaciones</label>
            <textarea type="textarea" class="form-control" rows="4" name="observations" id="for_observations">@if($request->input('observations')){{$request->input('observations')}}@endif </textarea>
        </fieldset>
    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>


</form>

@endsection

@section('custom_js')

@endsection
