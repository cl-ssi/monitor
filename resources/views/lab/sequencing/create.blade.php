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

<h3 class="mb-3"><i class="fas fa-folder-plus"></i> Agregar Datos de Secuenciación de {{$suspect_case->patient->FullName ?? ''}}</h3>
<h3>Caso Sospecha: {{$suspect_case->id ?? ''}}</h3>

<form method="POST" class="form-horizontal" action="#">

    <div class="form-row">
        <fieldset class="form-group col-12 col-sm-7 col-md-4">
            <label for="for_critery">Criterio*</label>
            <select name="critery" id="for_critery" class="form-control">
                <option value="">Seleccionar Criterio</option>
                <option value="Embarazada">Embarazada</option>
                <option value="Brote">Brote</option>
                <option value="Irag">Irag</option>
                <option value="PIMS">PIMS</option>
                <option value="Reinfección">Reifección</option>
                <option value="Inusitado">Inusitado</option>
            </select>
        </fieldset>

        <!-- <fieldset class="form-group col-12 col-sm-6 col-md-4">
            <label for="for_fathers_family">Fecha de Envío Secuenciación*</label>
            <input type="date" class="form-control" id="for_fathers_family" name="fathers_family" style="text-transform: uppercase;" autocomplete="off">
        </fieldset> -->

        <fieldset class="form-group col-12 col-sm-6 col-md-4">
            <label for="for_symptoms_at">Fecha Inicio Sintomas</label>
            <input type="date" class="form-control" id="for_symptoms_at" name="symptoms_at" autocomplete="off">
        </fieldset>
    </div>

    <div class="form-row">
        <fieldset class="form-group col-12 col-sm-7 col-md-4">
            <label for="for_name">Vacunación</label>
            <select name="vaccination" id="for_vaccination" class="form-control">
                <option value="">Seleccionar Tipo de Vacunación</option>
                <option value="Completa">Completa</option>
                <option value="Incompleta">Incompleta</option>
                <option value="Sin Vacunación">Sin Vacunación</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-12 col-sm-7 col-md-4">
            <label for="for_last_dose_at">Fecha Ultima Dosis</label>
            <input type="date" class="form-control" id="for_last_dose_at" name="last_dose_at" autocomplete="off">
        </fieldset>
    </div>

    <div class="form-row">
        <fieldset class="form-group col-12 col-sm-6 col-md-4">
            <label for="for_fathers_family">Estado de Hospitalización</label>
            <select name="hospitalization_status" id="for_hospitalization_status" class="form-control">
                <option value="">Seleccionar Estado</option>
                <option value="Ambulatorio">Ambulatorio</option>
                <option value="Hospitalizado">Hospitalizado</option>
            </select>
        </fieldset>


        <fieldset class="form-group col-12 col-sm-6 col-md-4">
            <label for="for_upc">UPC</label>
            <select name="upc" id="for_upc" class="form-control">
                <option value="">Seleccionar UPC</option>
                <option value="Sí">Sí</option>
                <option value="No">No</option>
            </select>
        </fieldset>


    </div>


    <div class="form-row">

        <fieldset class="form-group col-12 col-sm-12 col-md-12">
            <label for="for_diagnosis">Diagnostico*</label>
            <textarea type="text" class="form-control" id="for_diagnosis" name="diagnosis" style="text-transform: uppercase;" autocomplete="off"></textarea>
        </fieldset>

    </div>



    <button type="submit" class="btn btn-primary">Guardar</button>

    <a class="btn btn-outline-secondary" href="#">
        Cancelar
    </a>

</form>





@endsection

@section('custom_js')

@endsection