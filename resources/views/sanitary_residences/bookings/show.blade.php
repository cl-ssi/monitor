@extends('layouts.app')

@section('title', 'Ver booking')

@section('content')

@include('sanitary_residences.nav')
<h4 align="center"><u><b>{{ $booking->patient->fullName }}</b></u></h4>

<div class="row">
    <div class="col-9 col-md-9 font-weight-bold p-2">
        @canany(['Patient: edit','Patient: demographic edit'])
        <a href="{{ route('patients.edit', $booking->patient) }}"><h4>{{ $booking->patient->fullName }}</h4></a>
        @endcan
    </div>
    @can('SanitaryResidence: admin')
        <div class="col-3 col-md-3 font-weight-bold p-2">
            <form method="POST" class="form-horizontal" action="{{ route('sanitary_residences.bookings.destroy', $booking) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro que desea ELIMINAR el Booking del paciente {{ $booking->patient->fullName }} para la habitación {{$booking->room->number}} de la Residencia {{$booking->room->residence->name}}? ' )">Eliminar Booking</button>
            </form>
        </div>
    @endcan
</div>

<div class="row">
    <div class="col-12 col-md-3 p-2">
        <strong>Residencia: </strong>{{ $booking->room->residence->name }}
    </div>

    <div class="col-12 col-md-2 p-2">
        <strong>Habitacion: </strong>{{ $booking->room->number }}
    </div>

    <div class="col-6 col-md-3 p-2">
        <strong>Ingreso: </strong>{{ $booking->from->format('d-m-Y H:i') }}
    </div>

    <div class="col-6 col-md-3 p-2">
        <strong>Salida: </strong>{{ ($booking->to)?$booking->to->format('d-m-Y H:i'):'' }}
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
        <strong>Fecha Última Muestra: </strong>
        @if($booking->patient->suspectCases->last())
        {{$booking->patient->suspectCases->last()->sample_at->format('d-m-Y')}}
        @endif        
    </div>

    <div class="col-6 col-md-5 p-2">
        <strong>Fecha Último Resultado: </strong>
        @if($booking->patient->suspectCases->last() and $booking->patient->suspectCases->last()->pcr_sars_cov_2_at)
        {{$booking->patient->suspectCases->last()->pcr_sars_cov_2_at->format('d-m-Y')}}
        @endif        
    </div>

    <div class="col-12 col-md-4 p-2">
        <strong>Último Resultado: </strong>        
        {{ $booking->patient->suspectCases->last()? $booking->patient->suspectCases->last()->covid19:'' }}
    </div>

</div>

<hr>


<form method="POST" class="form-horizontal" action="{{ route('sanitary_residences.bookings.update', $booking) }}">
    @csrf
    @method('PUT')

    <div class="form-row">
        <fieldset class="form-group col-12 col-md-6">
            <label for="for_patient_id">Paciente</label>
            <select name="patient_id" id="for_patient_id" class="form-control">
                @foreach($patients as $patient)
                @can('SanitaryResidence: admin')
                <option value="{{ $patient->id }}" {{ ($patient->id == $booking->patient_id)?'selected':'' }}>{{ $patient->fullName }}</option>
                @endcan
                @canany(['SanitaryResidence: user', 'SanitaryResidence: view'])
                <option value="{{ $patient->id }}" {{ ($patient->id == $booking->patient_id)?'selected':'disabled' }}>{{ $patient->fullName }}</option>
                @endcan                
                @endforeach
            </select>
        </fieldset>

        <fieldset class="form-group col-12 col-md-4">
            <label for="for_room_id">Residencia - Habitación</label>
            <select name="room_id" id="for_room_id" class="form-control">
                @foreach(Auth::user()->residences as $residence)
                    @foreach($residence->rooms->sortBy('number') as $room)
                    @can('SanitaryResidence: admin')
                    <option value="{{ $room->id }}" {{ ($room->id == $booking->room_id)?'selected':'' }}>{{ $room->residence->name }} - Habitación {{ $room->number }}</option>
                    @endcan
                    @canany(['SanitaryResidence: user', 'SanitaryResidence: view'])
                    <option value="{{ $room->id }}" {{ ($room->id == $booking->room_id)?'selected':'disabled' }}>{{ $room->residence->name }} - Habitación {{ $room->number }}</option>
                    @endcan
                    @endforeach
                @endforeach
            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_prevision">Previsión</label>
            <select name="prevision" id="for_prevision" class="form-control">
                <option value="Sin Previsión" {{ ($booking->prevision == 'Sin Previsión')?'selected':'' }}>Sin Previsión</option>
                <option value="Fonasa A" {{ ($booking->prevision == 'Fonasa A')?'selected':'' }}>Fonasa A</option>
                <option value="Fonasa B" {{ ($booking->prevision == 'Fonasa B')?'selected':'' }}>Fonasa B</option>
                <option value="Fonasa C" {{ ($booking->prevision == 'Fonasa C')?'selected':'' }}>Fonasa C</option>
                <option value="Fonasa D" {{ ($booking->prevision == 'Fonasa D')?'selected':'' }}>Fonasa D</option>
                <option value="Fonasa E" {{ ($booking->prevision == 'Fonasa E')?'selected':'' }}>Fonasa E</option>
                <option value="ISAPRE" {{ ($booking->prevision == 'ISAPRE')?'selected':'' }}>ISAPRE</option>
                <option value="ISAPRE" {{ ($booking->prevision == 'OTRO')?'selected':'' }}>ISAPRE</option>
            </select>
        </fieldset>

    </div>

    <div class="form-row">

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_from">Desde</label>
            <input type="datetime-local" class="form-control date" name="from" id="for_from" value="{{$booking->from->format('Y-m-d\TH:i:s')}}" required>
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_to">Hasta (Estimado)</label>
            <input type="datetime-local" class="form-control date" name="to" id="for_to"  @if($booking->to) value="{{$booking->to->format('Y-m-d\TH:i:s')}}" @endif >

        </fieldset>

        <fieldset class="form-group col-5 col-md-2">
            <label for="for_length_of_stay">Estadía (Días)</label>
            <input type="number" class="form-control" name="length_of_stay" id="for_length_of_stay" value="{{$booking->length_of_stay}}">
        </fieldset>

        <!-- <fieldset class="form-group col-7 col-md-4">
            <label for="for_entry_criteria">Criterio de Ingreso</label>
            <input type="text" class="form-control" name="entry_criteria" id="for_entry_criteria" value="{{$booking->entry_criteria}}">
        </fieldset> -->

        <fieldset class="form-group col-7 col-md-4">
            <label for="for_prevision">Criterio de Ingreso</label>
            <select name="entry_criteria" id="for_entry_criteria" class="form-control" required>
                <option value="">Seleccione Condición</option>
                <option value="PCR +" {{ ($booking->entry_criteria == 'PCR +')?'selected':'' }}>PCR +</option>
                <option value="Otro" {{ ($booking->entry_criteria == 'Otro')?'selected':'' }}>Otro</option>
                <option value="Contacto Estrecho" {{ ($booking->entry_criteria == 'Contacto Estrecho')?'selected':'' }} >Contacto Estrecho</option>
                <option value="Sospecha" {{ ($booking->entry_criteria == 'Sospecha')?'selected':'' }} >Sospecha</option>
                <option value="Probable" {{ ($booking->entry_criteria == 'Probable')?'selected':'' }} >Probable</option>
                <option value="Viajero" {{ ($booking->entry_criteria == 'Viajero')?'selected':'' }} >Viajero</option>
            </select>
        </fieldset>

    </div>

    <div class="form-row">

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_responsible_family_member">Familiar Responsable/Teléfono</label>
            <input type="text" class="form-control" name="responsible_family_member" id="for_responsible_family_member" autocomplete="off" value="{{$booking->responsible_family_member}}">
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_relationship">Parentesco</label>
            <input type="text" class="form-control" name="relationship" id="for_relationship" value="{{$booking->relationship}}">
        </fieldset>

        <fieldset class="form-group col-6 col-md-3">
            <label for="for_onset_on_symptoms">Fecha de Inicio de Sintomas</label>
            <input type="date" class="form-control" name="onset_on_symptoms" id="for_onset_on_symptoms" value="{{$booking->onset_on_symptoms}}">
        </fieldset>

        <fieldset class="form-group col-6 col-md-3">
            <label for="for_end_of_symptoms">Fecha de Termino de Sintomas</label>
            <input type="date" class="form-control" name="end_of_symptoms" id="for_end_of_symptoms" value="{{$booking->end_of_symptoms}}">
        </fieldset>

    </div>

    <div class="form-row">

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_doctor">Doctor</label>
            <input type="text" class="form-control" name="doctor" id="for_doctor" value="{{$booking->doctor}}">
        </fieldset>

        <fieldset class="form-group col-12 col-md-9">
            <label for="for_diagnostic">Diagnostico</label>
            <input type="text" class="form-control" name="diagnostic" id="for_diagnostic" value="{{$booking->diagnostic}}">
        </fieldset>

    </div>

    <div class="form-row">

        <fieldset class="form-group col-12 col-md-6">
            <label for="for_allergies">Alergias</label>
            <input type="text" class="form-control" name="allergies" id="for_allergies" autocomplete="off" value="{{$booking->allergies}}">
        </fieldset>

        <fieldset class="form-group col-12 col-md-6">
            <label for="for_commonly_used_drugs">Farmacos de Uso Común</label>
            <input type="text" class="form-control" name="commonly_used_drugs" id="for_commonly_used_drugs" autocomplete="off" value="{{$booking->commonly_used_drugs}}">
        </fieldset>
    </div>


    <div class="form-row">
        <fieldset class="form-group col-12">
            <label for="for_morbid_history">Antecedentes Mórbidos</label>
            <textarea class="form-control" id="for_morbid_history" rows="2" name="morbid_history">{{$booking->morbid_history}}</textarea>
        </fieldset>
    </div>


    <div class="form-row">
        <fieldset class="form-group col-12 col-md-7">
            <label for="for_indications">Indicaciones</label>
            <textarea class="form-control" id="for_indications" rows="6" name="indications">{{$booking->indications}}</textarea>
        </fieldset>

        <fieldset class="form-group col-12 col-md-5">
            <label for="for_observations">Observaciones</label>
            <textarea type="textarea" class="form-control" rows="6" name="observations" id="for_observations">{{$booking->observations}}</textarea>
        </fieldset>

    </div>
    @canany(['SanitaryResidence: user', 'SanitaryResidence: admin'] )
    <button type="submit" class="btn btn-primary">Guardar</button>
    @endcan
    <a class="btn btn-outline-secondary" href="{{ route('sanitary_residences.home') }}">Cancelar</a>

</form>

<hr>
@include('sanitary_residences.vital_signs.partials.index', compact('booking'))
<hr>
@include('sanitary_residences.vital_signs.partials.create', compact('booking'))
<hr>
@include('sanitary_residences.evolutions.partials.index', compact('booking'))
<hr>
@include('sanitary_residences.evolutions.partials.create', compact('booking'))
<hr>
@include('sanitary_residences.indications.partials.index', compact('booking'))
<hr>
@include('sanitary_residences.indications.partials.create', compact('booking'))
<hr>
@include('sanitary_residences.bookings.medical_release.create', compact('booking'))

@endsection

@section('custom_js')

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script>
  $(document).ready(function() {
    @foreach($booking->vitalSigns as $vitalSigns)
      $("#btn_{{$vitalSigns->id}}").click(function(){

          $('#for_id').val({{$vitalSigns->id}});
          $("#for_created_at").val("{{$vitalSigns->created_at->format('Y-m-d')}}"+"T"+"{{$vitalSigns->created_at->format('H:i')}}");
          $('#for_temperature').val("{{$vitalSigns->temperature}}");
          $('#for_heart_rate').val("{{$vitalSigns->heart_rate}}");
          $('#for_blood_pressure').val("{{$vitalSigns->blood_pressure}}");
          $('#for_respiratory_rate').val("{{$vitalSigns->respiratory_rate}}");
          $('#for_oxygen_saturation').val("{{$vitalSigns->oxygen_saturation}}");
          $('#for_hgt').val("{{$vitalSigns->hgt}}");
          $('#for_pain_scale').val("{{$vitalSigns->pain_scale}}");
          $('#for_observations2').val("{{$vitalSigns->observations}}");

      });
    @endforeach


    @foreach($booking->evolutions as $evolutions)
      $("#btn_evoluciones_{{$evolutions->id}}").click(function(){
          $('#evolution_id').val({{$evolutions->id}});
          $("#for_evolution_created_at").val("{{$evolutions->created_at->format('Y-m-d')}}"+"T"+"{{$evolutions->created_at->format('H:i')}}");
          //se reemplaza el enter por espacio
          $('#for_content').val("{{  preg_replace("~[\r\n]+~","\\n", ($evolutions->content))  }}");

      });
    @endforeach

    @foreach($booking->indicaciones as $indications)
      $("#btn_indications_{{$indications->id}}").click(function(){
          $('#indication_id').val({{$indications->id}});
          $("#for_indication_created_at").val("{{$indications->created_at->format('Y-m-d')}}"+"T"+"{{$indications->created_at->format('H:i')}}");
          //se reemplaza el enter por espacio
          //$('#for_indication_content').val("{{  preg_replace("~[\r\n]+~"," ", ($indications->content))  }}");
          $('#for_indication_content').val("{{  preg_replace("~[\r\n]+~","\\n", ($indications->content))  }}");

      });
    @endforeach



  });
  </script>

@endsection
