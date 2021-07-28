@extends('layouts.app')

@section('title', 'Listado de Pacientes Candidatos a Secuenciación')

@section('content')
<ul class="nav nav-tabs mb-3 d-print-none">

    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fas fa-ban"></i> Sin Datos
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fas fa-info-circle"></i> Con Datos
        </a>
    </li>
</ul>


<h3 class="mb-3"><i class="fas fa-folder-plus"></i> Agregar Datos de Secuenciación de {{$sequencingCriteria->suspectCase->patient->FullName ?? ''}}</h3>
<h3>Caso Sospecha: {{$sequencingCriteria->suspect_case_id ?? ''}}</h3>

<form method="POST" class="form-horizontal" action="{{ route('sequencing.update', $sequencingCriteria) }}">
    @csrf
    @method('PUT')

    <div class="form-row">
        <fieldset class="form-group col-12 col-sm-7 col-md-4">
            <label for="for_critery">Criterio*</label>
            <select name="critery" id="for_critery" class="form-control">                
                <option value="Sin Criterio" {{ ($sequencingCriteria->critery == 'Sin Criterio')?'selected':'' }}>Sin Criterio</option>
                <option value="Embarazada" {{ ($sequencingCriteria->critery == 'Embarazada')?'selected':'' }} >Embarazada</option>
                <option value="Brote" {{ ($sequencingCriteria->critery == 'Brote')?'selected':'' }} >Brote</option>
                <option value="Irag" {{ ($sequencingCriteria->critery == 'Irag')?'selected':'' }} >Irag</option>
                <option value="PIMS" {{ ($sequencingCriteria->critery == 'PIMS')?'selected':'' }} >PIMS</option>
                <option value="Reinfección" {{ ($sequencingCriteria->critery == 'Reinfección')?'selected':'' }} >Reinfección</option>
                <option value="Inusitado" {{ ($sequencingCriteria->critery == 'Inusitado')?'selected':'' }} >Inusitado</option>                
            </select>
        </fieldset>

        <!-- <fieldset class="form-group col-12 col-sm-6 col-md-4">
            <label for="for_fathers_family">Fecha de Envío Secuenciación*</label>
            <input type="date" class="form-control" id="for_fathers_family" name="fathers_family" style="text-transform: uppercase;" autocomplete="off">
        </fieldset> -->

        <fieldset class="form-group col-12 col-sm-6 col-md-4">
            <label for="for_symptoms_at">Fecha Inicio Sintomas</label>
            <input type="date" class="form-control" id="for_symptoms_at" name="symptoms_at" autocomplete="off" value="{{$sequencingCriteria->symptoms_at}}">
        </fieldset>
    </div>

    <div class="form-row">
        <fieldset class="form-group col-12 col-sm-7 col-md-4">
            <label for="for_name">Vacunación</label>
            <select name="vaccination" id="for_vaccination" class="form-control">
                <option value="">Seleccionar Tipo de Vacunación</option>
                <option value="Completa" {{ ($sequencingCriteria->vaccination == 'Hospitalizado')?'selected':'' }} >Completa</option>
                <option value="Incompleta" {{ ($sequencingCriteria->vaccination == 'Incompleta')?'selected':'' }} >Incompleta</option>
                <option value="Sin Vacunación" {{ ($sequencingCriteria->vaccination == 'Sin Vacunación')?'selected':'' }} >Sin Vacunación</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-12 col-sm-7 col-md-4">
            <label for="for_last_dose_at">Fecha Ultima Dosis</label>
            <input type="date" class="form-control" id="for_last_dose_at" name="last_dose_at" autocomplete="off" value="{{$sequencingCriteria->last_dose_at}}">
        </fieldset>
    </div>

    <div class="form-row">
        <fieldset class="form-group col-12 col-sm-6 col-md-4">
            <label for="for_fathers_family">Estado de Hospitalización</label>
            <select name="hospitalization_status" id="for_hospitalization_status" class="form-control">
                <option value="">Seleccionar Estado</option>
                <option value="Ambulatorio" {{ ($sequencingCriteria->hospitalization_status == 'Ambulatorio')?'selected':'' }} >Ambulatorio</option>
                <option value="Hospitalizado" {{ ($sequencingCriteria->hospitalization_status == 'Hospitalizado')?'selected':'' }} >Hospitalizado</option>
            </select>
        </fieldset>


        <fieldset class="form-group col-12 col-sm-6 col-md-4">
            <label for="for_upc">UPC</label>
            <select name="upc" id="for_upc" class="form-control">
                <option value="">Seleccionar UPC</option>                
                <option value="1" {{ ($sequencingCriteria->upc == '1')?'selected':'' }} >Sí</option>
                <option value="0" {{ ($sequencingCriteria->upc == '0')?'selected':'' }} >No</option>
            </select>
        </fieldset>
    </div>
    <hr>
    <h4>Sintomas</h4>
    <div class="form-check">
        <input type="hidden" name="fever" value="0">
        <input class="form-check-input" type="checkbox" name="fever" {{ $sequencingCriteria->fever?'checked':'' }} value="1">
        <label class="form-check-label">
            Fiebre sobre 38°
        </label>
    </div>
    <div class="form-check">
        <input type="hidden" name="throat_pain" value="0">
        <input class="form-check-input" type="checkbox" name="throat_pain" {{ $sequencingCriteria->throat_pain?'checked':'' }} value="1">
        <label class="form-check-label">
            Dolor de Garganta
        </label>
    </div>
    <div class="form-check">
        <input type="hidden" name="myalgia" value="0">
        <input class="form-check-input" type="checkbox" name="myalgia" {{ $sequencingCriteria->myalgia?'checked':'' }} value="1">
        <label class="form-check-label">
            Mialgia
        </label>
    </div>
    <div class="form-check">
        <input type="hidden" name="pneumonia" value="0">
        <input class="form-check-input" type="checkbox" name="pneumonia" {{ $sequencingCriteria->pneumonia?'checked':'' }} value="1">
        <label class="form-check-label">
            Neumonía
        </label>
    </div>
    <div class="form-check">
        <input type="hidden" name="encephalitis" value="0">
        <input class="form-check-input" type="checkbox" name="encephalitis" {{ $sequencingCriteria->encephalitis?'checked':'' }} value="1">
        <label class="form-check-label">
            Encefalitis
        </label>
    </div>
    <div class="form-check">
        <input type="hidden" name="cough" value="0">
        <input class="form-check-input" type="checkbox" name="cough" {{ $sequencingCriteria->cough?'checked':'' }} value="1">
        <label class="form-check-label">
            Tos
        </label>
    </div>
    <div class="form-check">
        <input type="hidden" name="rhinorrhea" value="0">
        <input class="form-check-input" type="checkbox" name="rhinorrhea" {{ $sequencingCriteria->rhinorrhea?'checked':'' }} value="1">
        <label class="form-check-label">
            Rinorrea/Congestión Nasal
        </label>
    </div>
    <div class="form-check">
        <input type="hidden" name="respiratory_distress" value="0">
        <input class="form-check-input" type="checkbox" name="respiratory_distress" {{ $sequencingCriteria->respiratory_distress?'checked':'' }} value="1">
        <label class="form-check-label">
            Dificultad Respiratoria
        </label>
    </div>
    <div class="form-check">
        <input type="hidden" name="hypotension" value="0">
        <input class="form-check-input" type="checkbox" name="hypotension" {{ $sequencingCriteria->hypotension?'checked':'' }} value="1">
        <label class="form-check-label">
            Hipotensión
        </label>
    </div>
    <div class="form-check">
        <input type="hidden" name="headache" value="0">
        <input class="form-check-input" type="checkbox" name="headache" {{ $sequencingCriteria->headache?'checked':'' }} value="1">
        <label class="form-check-label">
            Cefalea
        </label>
    </div>
    <div class="form-check">
        <input type="hidden" name="tachypnea" value="0">
        <input class="form-check-input" type="checkbox" name="tachypnea" {{ $sequencingCriteria->tachypnea?'checked':'' }} value="1">
        <label class="form-check-label">
            Taquipnea
        </label>
    </div>
    <div class="form-check">
        <input type="hidden" name="hypoxia" value="0">
        <input class="form-check-input" type="checkbox" name="hypoxia" {{ $sequencingCriteria->hypoxia?'checked':'' }} value="1">
        <label class="form-check-label">
            Hipoxia
        </label>
    </div>
    <div class="form-check">
        <input type="hidden" name="cyanosis" value="0">
        <input class="form-check-input" type="checkbox" name="cyanosis" {{ $sequencingCriteria->cyanosis?'checked':'' }} value="1">
        <label class="form-check-label">
            Cianosis
        </label>
    </div>
    <div class="form-check">
        <input type="hidden" name="food_refusal" value="0">
        <input class="form-check-input" type="checkbox" name="food_refusal" {{ $sequencingCriteria->food_refusal?'checked':'' }} value="1">
        <label class="form-check-label">
            Deshidratación o rechazo alimentario (lactantes)
        </label>
    </div>
    <div class="form-check">
        <input type="hidden" name="hemodynamic_compromise" value="0">
        <input class="form-check-input" type="checkbox" name="hemodynamic_compromise" {{ $sequencingCriteria->hemodynamic_compromise?'checked':'' }} value="1">
        <label class="form-check-label">
            Compromiso hemodinámica
        </label>
    </div>
    <div class="form-check">
        <input type="hidden" name="respiratory_condition_deterioration" value="0">
        <input class="form-check-input" type="checkbox" name="respiratory_condition_deterioration" {{ $sequencingCriteria->respiratory_condition_deterioration?'checked':'' }} value="1">
        <label class="form-check-label">
            Consulta repetida por deterioro cuadro respiratorio
        </label>
    </div>
    <div class="form-check">
        <input type="hidden" name="ageusia" value="0">
        <input class="form-check-input" type="checkbox" name="ageusia" {{ $sequencingCriteria->ageusia?'checked':'' }} value="1">
        <label class="form-check-label">
            Ageusia
        </label>
    </div>

    <div class="form-check">
        <input type="hidden" name="anosmia" value="0">
        <input class="form-check-input" type="checkbox" name="anosmia" {{ $sequencingCriteria->anosmia?'checked':'' }} value="1">
        <label class="form-check-label">
            Anosmia
        </label>
    </div>



    <hr>

    <div class="form-row">
        <fieldset class="form-group col-12 col-sm-6 col-md-4">
            <label for="for_underlying_disease">Enfermedad de Base</label>
            <input name="underlying_disease" id="for_underlying_disease" class="form-control" value="{{ $sequencingCriteria->underlying_disease }}" autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-12 col-sm-12 col-md-12">
            <label for="for_diagnosis">Diagnostico</label>
            <textarea type="text" class="form-control" id="for_diagnosis" name="diagnosis" style="text-transform: uppercase;" autocomplete="off">{{$sequencingCriteria->diagnosis }}</textarea>
        </fieldset>

    </div>



    <button type="submit" class="btn btn-primary">Guardar</button>
    <!-- 
    <a class="btn btn-outline-secondary" href="#">
        Cancelar
    </a> -->

</form>





@endsection

@section('custom_js')

@endsection