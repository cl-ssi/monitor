@extends('layouts.app')

@section('title', 'Ver Booking Egresado')

@section('content')

@include('sanitary_residences.nav')


<div class="row">
    <div class="col-12 col-md-12 font-weight-bold p-2">
        <h4>{{ $booking->patient->fullName }}</h4>

    </div>
</div>

<div class="row">
    <div class="col-12 col-md-3 p-2">
        <strong>Residencia: </strong>{{ $booking->room->residence->name }}
    </div>

    <div class="col-12 col-md-2 p-2">
        <strong>Habitacion: </strong>{{ $booking->room->number }}
    </div>

    <div class="col-6 col-md-3 p-2">
        <strong>Ingreso: </strong>{{ $booking->from }}
    </div>


    <div class="col-6 col-md-3 p-2">
        <strong>Salida Real: </strong>{{ ($booking->real_to)?($booking->real_to):'Paciente No Ha Sido Egresado de Residencia Sanitaria' }}
    </div>

</div>

<div class="row">

    <div class="col-6 col-md-3 p-2">
        <strong>Run o (ID): </strong>
        {{ $booking->patient->identifier }}
    </div>

    <div class="col-6 col-md-2 p-2">
        <strong>Género: </strong>
        {{ $booking->patient->sexEsp }}
    </div>

    <div class="col-12 col-md-3 p-2">
        <strong>Fecha Nac.: </strong>
        {{ ($booking->patient->birthday)? $booking->patient->birthday->format('d-m-Y'):'' }}
    </div>

    <div class="col-12 col-md-4 p-2">
        <strong>Procedencia: </strong>
        {{ ($booking->patient->suspectCases->last())? $booking->patient->suspectCases->last()->origin:'' }}
    </div>

</div>

<div class="row">

    <div class="col-12 col-md-12 p-2">
        <strong>Dirección: </strong>
        {{ ($booking->patient->demographic)?$booking->patient->demographic->address:'' }}
        {{ ($booking->patient->demographic)?$booking->patient->demographic->number:'' }} -
        {{ ($booking->patient->demographic)?$booking->patient->demographic->commune->name:'' }}
    </div>

</div>

<div class="row">

    <div class="col-12 col-md-3 p-2">
        <strong>Teléfono: </strong>
        {{ ($booking->patient->demographic)?$booking->patient->demographic->telephone:'' }}
    </div>

    <div class="col-12 col-md-5 p-2">
        <strong>Email: </strong>
        {{ ($booking->patient->demographic)?$booking->patient->demographic->email:'' }}
    </div>

    <div class="col-12 col-md-3 p-2">
        <strong>Nacionalidad: </strong>
        {{ ($booking->patient->demographic)?$booking->patient->demographic->nationality:'' }}
    </div>

</div>

<div class="row">

    <div class="col-6 col-md-3 p-2">
        <strong>Fecha Muestra: </strong>
        {{ ($booking->patient->suspectCases->where('pcr_sars_cov_2', 'positive')->last())? $booking->patient->suspectCases->where('pcr_sars_cov_2', 'positive')->last()->sample_at->format('d-m-Y'):''  }}
    </div>

    <div class="col-6 col-md-5 p-2">
        <strong>Fecha Resultado: </strong>
        {{ ($booking->patient->suspectCases->where('pcr_sars_cov_2', 'positive')->last())? $booking->patient->suspectCases->where('pcr_sars_cov_2', 'positive')->last()->pcr_sars_cov_2_at->format('d-m-Y'):''  }}
    </div>

    <div class="col-12 col-md-4 p-2">
        <strong>Resultado: </strong>
        {{ $booking->patient->suspectCases->where('pcr_sars_cov_2', 'positive')->last()? $booking->patient->suspectCases->where('pcr_sars_cov_2', 'positive')->last()->covid19:'' }}
    </div>

</div>

<hr>


<form method="POST" class="form-horizontal" action="{{ route('sanitary_residences.bookings.update', $booking) }}">
    @csrf
    @method('PUT')

    <div class="form-row">
        <fieldset class="form-group col-12 col-md-6">
            <label for="for_patient_id">Paciente</label>
            <select name="patient_id" id="for_patient_id" class="form-control" readonly>
                <option value="{{ $booking->patient->id }}">{{ $booking->patient->fullName }}</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-12 col-md-4">
            <label for="for_room_id">Residencia - Habitación</label>
            <select name="room_id" id="for_room_id" class="form-control" readonly>
                <option>{{ $booking->room->residence->name }} - Habitación {{ $booking->room->number }}</option>

            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_prevision">Previsión</label>
            <select name="prevision" id="for_prevision" class="form-control" readonly>
                <option>{{ $booking->prevision }}</option>
            </select>
        </fieldset>

    </div>

    <div class="form-row">

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_from">Desde</label>
            <input type="datetime-local" class="form-control date" name="from" id="for_from" value="{{$booking->from->format('Y-m-d\TH:i:s')}}" required readonly>
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_to">Hasta (Estimado)</label>
            <input type="datetime-local" class="form-control date" name="to" id="for_to" @if($booking->to) value="{{$booking->to->format('Y-m-d\TH:i:s')}}" @endif required readonly>
        </fieldset>

        <fieldset class="form-group col-5 col-md-2">
            <label for="for_length_of_stay">Estadía (Días)</label>
            <input type="number" class="form-control" name="length_of_stay" id="for_length_of_stay" value="{{$booking->length_of_stay}}" readonly>
        </fieldset>

        <fieldset class="form-group col-7 col-md-4">
            <label for="for_entry_criteria">Criterio de Ingreso</label>
            <input type="text" class="form-control" name="entry_criteria" id="for_entry_criteria" value="{{$booking->entry_criteria}}" readonly>
        </fieldset>

    </div>

    <div class="form-row">

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_responsible_family_member">Familiar Responsable</label>
            <input type="text" class="form-control" name="responsible_family_member" id="for_responsible_family_member" autocomplete="off" value="{{$booking->responsible_family_member}}" readonly>
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_relationship">Parentesco</label>
            <input type="text" class="form-control" name="relationship" id="for_relationship" value="{{$booking->relationship}}" readonly>
        </fieldset>

        <fieldset class="form-group col-6 col-md-3">
            <label for="for_onset_on_symptoms">Fecha de Inicio de Sintomas</label>
            <input type="date" class="form-control" name="onset_on_symptoms" id="for_onset_on_symptoms" value="{{$booking->onset_on_symptoms}}" readonly>
        </fieldset>

        <fieldset class="form-group col-6 col-md-3">
            <label for="for_end_of_symptoms">Fecha de Termino de Sintomas</label>
            <input type="date" class="form-control" name="end_of_symptoms" id="for_end_of_symptoms" value="{{$booking->end_of_symptoms}}" readonly>
        </fieldset>

    </div>

    <div class="form-row">

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_doctor">Doctor</label>
            <input type="text" class="form-control" name="doctor" id="for_doctor" value="{{$booking->doctor}}" readonly>
        </fieldset>

        <fieldset class="form-group col-12 col-md-9">
            <label for="for_diagnostic">Diagnostico</label>
            <input type="text" class="form-control" name="diagnostic" id="for_diagnostic" value="{{$booking->diagnostic}}" readonly>
        </fieldset>

    </div>

    <div class="form-row">

        <fieldset class="form-group col-12 col-md-6">
            <label for="for_allergies">Alergias</label>
            <input type="text" class="form-control" name="allergies" id="for_allergies" autocomplete="off" value="{{$booking->allergies}}" readonly>
        </fieldset>

        <fieldset class="form-group col-12 col-md-6">
            <label for="for_commonly_used_drugs">Farmacos de Uso Común</label>
            <input type="text" class="form-control" name="commonly_used_drugs" id="for_commonly_used_drugs" autocomplete="off" value="{{$booking->commonly_used_drugs}}" readonly>
        </fieldset>
    </div>


    <div class="form-row">
        <fieldset class="form-group col-12">
            <label for="for_morbid_history">Antecedentes Mórbidos</label>
            <textarea class="form-control" id="for_morbid_history" rows="2" name="morbid_history" readonly>{{$booking->morbid_history}}</textarea>
        </fieldset>
    </div>


    <div class="form-row">
        <fieldset class="form-group col-12 col-md-7">
            <label for="for_indications">Indicaciones</label>
            <textarea class="form-control" id="for_indications" rows="6" name="indications" readonly>{{$booking->indications}}</textarea>
        </fieldset>

        <fieldset class="form-group col-12 col-md-5">
            <label for="for_observations">Observaciones</label>
            <textarea type="textarea" class="form-control" rows="6" name="observations" id="for_observations" readonly>{{$booking->observations}}</textarea>
        </fieldset>

    </div>

    <a href="{{ URL::previous() }}" class="btn btn-primary"> <i class="fas fa-arrow-left"></i> Volver</a>

</form>

<hr>
@include('sanitary_residences.vital_signs.partials.index', compact('booking'))
<hr>
@include('sanitary_residences.evolutions.partials.index', compact('booking'))
<hr>
@include('sanitary_residences.indications.partials.index', compact('booking'))
<hr>
@include('sanitary_residences.bookings.medical_release.partials.index', compact('booking'))

@can('Admin')

@include('partials.audit', ['audits' => $booking->audits] )
@endcan

@endsection

@section('custom_js')


@endsection