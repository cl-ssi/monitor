@extends('layouts.app')

@section('title', 'Crear Residencia')

@section('content')



<h3 class="mb-3">Pauta de Evaluación Residencia Sanitaria</h3>

<hr>
<form method="POST" class="form-horizontal" action="{{ route('sanitary_residences.admission.store') }}">
    @csrf
    @method('POST')

    <h5 class="mb-6">Identificación de Paciente</h5>
    <input type="hidden" id="patient_id" name="patient_id" value="{{$patient->id}}">

    <div class="form-row">
        <fieldset class="form-group col-8 col-md-8">
            <label for="for_name">Nombre completo</label>
            <input type="text" class="form-control" name="name" id="for_name" required placeholder="" autocomplete="off" value="{{$patient->fullname}}" readonly>
        </fieldset>

        <fieldset class="form-group col-4 col-md-4">
            <label for="for_address">RUT o ID</label>
            <input type="text" class="form-control" name="address" id="for_address" required placeholder="" autocomplete="off" value="{{$patient->identifier}}" readonly>
        </fieldset>
    </div>

    <div class="form-row">
        <fieldset class="form-group col-8 col-md-8">
            <label for="for_name">Domicilio</label>
            <input type="text" class="form-control" name="name" id="for_name" required placeholder="" autocomplete="off" value="{{($patient->demographic)?$patient->demographic->fulladdress:''}}" readonly>
        </fieldset>

        <fieldset class="form-group col-4 col-md-4">
            <label for="for_address">Nacionalidad</label>
            <input type="text" class="form-control" name="address" id="for_address" required placeholder="" autocomplete="off" value="{{ ($patient->demographic)?$patient->demographic->nationality:'' }}" readonly>
        </fieldset>
    </div>


    <div class="form-row">
        <fieldset class="form-group col-2 col-md-2">
            <label for="for_name">Edad</label>
            <input type="text" class="form-control" name="name" id="for_name" required placeholder="" autocomplete="off" value="{{ $patient->age }}" readonly>
        </fieldset>

        <fieldset class="form-group col-4 col-md-4">
            <label for="for_address">Fecha de Notificación de Resultado</label>
            <input type="text" class="form-control" name="address" id="for_address" required placeholder="" autocomplete="off" value="{{  ($patient->suspectCases->where('pscr_sars_cov_2', 'positive')->last())? $patient->suspectCases->where('pscr_sars_cov_2', 'positive')->last()->pscr_sars_cov_2_at->format('d-m-Y'):''   }}" readonly>
        </fieldset>


        <fieldset class="form-group col-2 col-md-2">
            <label for="for_name">Telefono</label>
            <input type="text" class="form-control" name="name" id="for_name" required placeholder="" autocomplete="off" value="{{ ($patient->demographic)?$patient->demographic->telephone:'' }}" readonly>
        </fieldset>
    </div>
    <hr>
    <h5 class="mb-6">Condiciones de habitabilidad</h5>

    <div class="form-row">

        <fieldset class="form-group col-3 col-md-3">
            <label for="for_name">¿Con cuantas personas habita?</label>
            <input type="number" class="form-control" name="people" id="for_people" required placeholder="" autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-4 col-md-4">
            <label for="for_name">¿Con cuantas habitaciones cuenta el hogar?</label>
            <input type="number" class="form-control" name="rooms" id="for_rooms" required placeholder="" autocomplete="off">
        </fieldset>

    </div>

    <hr>
    <div class="form-check">
        <h5 class="mb-6">¿CALIFICA RESIDENCIA?</h5>
        <input class="form-check-input" type="radio" name="residency" id="exampleRadios1" value="1" >
        <label class="form-check-label" for="exampleRadios1">
            SÍ
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="residency" id="exampleRadios2" value="0">
        <label class="form-check-label" for="exampleRadios2">
            NO
        </label>
    </div>





    <button type="submit" class="btn btn-primary">Guardar</button>

    <a class="btn btn-outline-secondary" href="{{ URL::previous() }}">Cancelar</a>

</form>


@endsection

@section('custom_js')

@endsection