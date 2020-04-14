@extends('layouts.app')

@section('title', 'Ver booking')

@section('content')

@include('sanitary_residences.nav')


<u>
    <strong>
        <h3 class="mb-3" align="center">{{ $booking->patient->fullName }}</h3>
        <h4 class="mb-3" align="center">{{ $booking->room->residence->name }}</h4>
        <h5 class="mb-3" align="center">Habitacion:{{ $booking->room->number }}</h5>
    </strong>
</u>

<hr>
<h5 class="mb-3" align="center">Ingreso:{{ $booking->from->format('d-m-Y H:i') }} - Salida Estimada:{{ $booking->to->format('d-m-Y H:i') }}</h5>

<table class="table table-bordered table-responsive">
    <thead>
        <tr>
            <th>Run o (ID)</th>
            <th>Genero</th>
            <th>Fecha Nac.</th>
            <th>Dirección/Comuna</th>
            <th>Teléfono/Email</th>
            <th>Procedencia</th>
            <th>Fecha Muestra</th>
            <th>Resultado</th>
            <th>Fecha Entrega de Resultado Lab</th>
        </tr>
    </thead>
    <tbody id="tablePatients">
        <td nowrap class="text-center">{{ $booking->patient->identifier }}</td>
        <td>{{ $booking->patient->genderEsp }}</td>
        <td nowrap>{{ ($booking->patient->birthday)? $booking->patient->birthday->format('d-m-Y'):'' }}</td>
        <td class="small">
            {{ ($booking->patient->demographic)?$booking->patient->demographic->address:'' }}
            {{ ($booking->patient->demographic)?$booking->patient->demographic->number:'' }}<br>
            {{ ($booking->patient->demographic)?$booking->patient->demographic->commune:'' }}
        </td>
        <td class="small">
            {{ ($booking->patient->demographic)?$booking->patient->demographic->telephone:'' }}<br>
            {{ ($booking->patient->demographic)?$booking->patient->demographic->email:'' }}
        </td>
        <td>{{ $booking->patient->suspectCases->last()->origin }}</td>
        <td nowrap>{{ ($booking->patient->suspectCases->last()->sample_at)? $booking->patient->suspectCases->last()->sample_at->format('d-m-Y'):''  }}</td>
        <td>{{ $booking->patient->suspectCases->last()->covid19 }}</td>
        <td nowrap>{{ ($booking->patient->suspectCases->last()->pscr_sars_cov_2_at)? $booking->patient->suspectCases->last()->pscr_sars_cov_2_at->format('d-m-Y'):''  }}</td>
    </tbody>
</table>


<form method="POST" class="form-horizontal" action="{{ route('sanitary_residences.bookings.update', $booking) }}">
    @csrf
    @method('PUT')

<div class="form-row">
    <fieldset class="form-group col-12 col-md-3">
        <label for="for_patient_id">Paciente</label>
        <select name="patient_id" id="for_patient_id" class="form-control">
            @foreach($patients as $patient)
            <option value="{{ $patient->id }}" {{ ($patient->id == $booking->patient_id)?'selected':'' }}>{{ $patient->fullName }}</option>
            @endforeach
        </select>
        {{-- <input type="text" class="form-control" name="patient_id" id="for_patient_id" value="{{ $booking->patient->identifier }}"> --}}
    </fieldset>

    <fieldset class="form-group col-12 col-md-3">
        <label for="for_room_id">Residence - Habitación</label>
        <select name="room_id" id="for_room_id" class="form-control">
            @foreach($rooms as $room)
            <option value="{{ $room->id }}" {{ ($room->id == $booking->room_id)?'selected':'' }}>{{ $room->residence->name }} - Habitación {{ $room->number }}</option>
            @endforeach
        </select>
        {{-- <input type="text" class="form-control" name="patient_id" id="for_patient_id" value="{{ $booking->patient->identifier }}"> --}}
    </fieldset>

    <fieldset class="form-group col-12 col-md-3">
        <label for="for_from">Desde</label>
        <input type="datetime-local" class="form-control date" name="from" id="for_from" value="{{$booking->from->format('Y-m-d\TH:i:s')}}" required>
    </fieldset>

    <fieldset class="form-group col-12 col-md-3">
        <label for="for_to">Hasta (Estimado)</label>
        <input type="datetime-local" class="form-control date" name="to" id="for_to" value="{{$booking->to->format('Y-m-d\TH:i:s')}}" required>
    </fieldset>

</div>


<div class="form-row">
    <fieldset class="form-group col-4 col-md-3">
        <label for="for_responsible_family_member">Familiar Responsable</label>
        <input type="text" class="form-control" name="responsible_family_member" id="for_responsible_family_member" autocomplete="off" value="{{$booking->responsible_family_member}}">
    </fieldset>

    <fieldset class="form-group col-4 col-md-3">
        <label for="for_relationship">Parentesco</label>
        <input type="text" class="form-control" name="relationship" id="for_relationship" value="{{$booking->relationship}}">
    </fieldset>

    {{-- <fieldset class="form-group col-4 col-md-3">
        <label for="for_diagnostic">Diagnostico</label>
        <input type="text" class="form-control" name="diagnostic" id="for_diagnostic" autocomplete="off" value="{{$booking->diagnostic}}">
    </fieldset> --}}

</div>

<div class="form-row">
    <fieldset class="form-group col-4 col-md-3">
        <label for="for_doctor">Doctor</label>
        <input type="text" class="form-control" name="doctor" id="for_doctor" value="{{$booking->doctor}}">
    </fieldset>

    <fieldset class="form-group col-4 col-md-3">
        <label for="for_entry_criteria">Criterio de Ingreso</label>
        <input type="text" class="form-control" name="entry_criteria" id="for_entry_criteria" value="{{$booking->entry_criteria}}">
    </fieldset>

    <fieldset class="form-group col-4 col-md-3">
        <label for="for_prevision">Previsión</label>
        <input type="text" class="form-control" name="prevision" id="for_prevision" value="{{$booking->prevision}}">
    </fieldset>

    <fieldset class="form-group col-4 col-md-3">
        <label for="for_length_of_stay">Tiempo de Estadía (Días)</label>
        <input type="number" class="form-control" name="length_of_stay" id="for_length_of_stay" value="{{$booking->length_of_stay}}">
    </fieldset>
</div>

{{-- <fieldset class="form-group">
    <label for="for_morbid_history">Antecedentes Mórbidos</label>
    <textarea class="form-control" id="for_morbid_history" rows="3" name="morbid_history" value="{{$booking->morbid_history}}"></textarea>
</fieldset> --}}


<div class="form-row">
    <fieldset class="form-group col-4 col-md-3">
        <label for="for_onset_on_symptoms">Fecha de Inicio de Sintomas</label>
        <input type="date" class="form-control" name="onset_on_symptoms" id="for_onset_on_symptoms" value="{{$booking->onset_on_symptoms}}">
    </fieldset>

    <fieldset class="form-group col-4 col-md-3">
        <label for="for_end_of_symptoms">Fecha de Termino de Sintomas</label>
        <input type="date" class="form-control" name="end_of_symptoms" id="for_end_of_symptoms" value="{{$booking->end_of_symptoms}}">
    </fieldset>

    <fieldset class="form-group col-4 col-md-3">
        <label for="for_allergies">Alergias</label>
        <input type="text" class="form-control" name="allergies" id="for_allergies" autocomplete="off" value="{{$booking->allergies}}">
    </fieldset>

    <fieldset class="form-group col-4 col-md-3">
        <label for="for_commonly_used_drugs">Farmacos de Uso Común</label>
        <input type="text" class="form-control" name="commonly_used_drugs" id="for_commonly_used_drugs" autocomplete="off" value="{{$booking->commonly_used_drugs}}">
    </fieldset>
</div>


<fieldset class="form-group">
    <label for="for_indications">Indicaciones</label>
    <textarea class="form-control" id="for_indications" rows="3" name="indications">{{$booking->indications}}</textarea>
</fieldset>

<fieldset class="form-group">
    <label for="for_observations">Observaciones</label>
    <textarea type="textarea" class="form-control" rows="3" name="observations" id="for_observations">{{$booking->observations}}</textarea>
</fieldset>

<button type="submit" class="btn btn-primary">Guardar</button>

</form>

<hr>

{{-- @if ($booking->indications <> null)
    <label class="font-weight-bold">Indicaciones:</label>
    <p>{{ $booking->indications }}</p>
    <hr>
@endif

@if ($booking->observations <> null)
    <label class="font-weight-bold">Observaciones:</label>
    <p>{{ $booking->observations }}</p>
    <hr>
@endif --}}


@include('sanitary_residences.vital_signs.partials.index', compact('booking'))

<hr>

@include('sanitary_residences.vital_signs.partials.create', compact('booking'))

@endsection

@section('custom_js')

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script>
  $(document).ready(function() {
    @foreach($booking->vitalSigns as $vitalSigns)
      $("#btn_{{$vitalSigns->id}}").click(function(){

          $('#for_id').val({{$vitalSigns->id}});
          $('#for_temperature').val({{$vitalSigns->temperature}});
          $('#for_heart_rate').val({{$vitalSigns->heart_rate}});
          $('#for_blood_pressure').val("{{$vitalSigns->blood_pressure}}");
          $('#for_respiratory_rate').val({{$vitalSigns->respiratory_rate}});
          $('#for_oxygen_saturation').val({{$vitalSigns->oxygen_saturation}});
          $('#for_hgt').val({{$vitalSigns->hgt}});
          $('#for_pain_scale').val({{$vitalSigns->pain_scale}});
          $('#for_observations2').val("{{$vitalSigns->observations}}");

      });
    @endforeach
  });
  </script>

@endsection
