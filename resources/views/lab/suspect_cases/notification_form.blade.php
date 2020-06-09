@extends('layouts.app')

@section('title', 'Crear Paciente')

@section('content')

    <div class="row">
        <div class="col-md-2">
            <img src="/images/256px_logo_isp.png" class="img-fluid" alt="Instituto de Salud Pública de Chile">
        </div>
        <div class="col-md-8">
            <h3 class="mb-3">Formulario notificación inmediata y envío de muestras
                a confirmación IRA grave y 2019-nCoV </h3>
        </div>
        <div class="col-md-2"></div>
    </div>
    <hr/>

    <form method="POST" class="form-horizontal" >
        @csrf
        @method('POST')

        <div class="row">
            <div class="col-md-4">
                <h4 class="mb-3">
                    Información del Paciente
                </h4>
            </div>
            <div class="col-md-4"></div>
            <div class="col-md-4"></div>
        </div>

        <div class="form-group row">
            <label for="for_run" class="col-sm-1 col-form-label">Rut</label>
            <div class="col-sm-4">
                <input type="number" class="form-control" id="for_run" placeholder="Run">
            </div>
            <div class="col-sm-1">
                <input type="text" class="form-control" id="for_dv" placeholder="dv" >
            </div>
            <label for="for_address" class="col-sm-1 col-form-label">Dirección</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_address" placeholder="Dirección">
            </div>
        </div>
        <div class="form-group row">
            <label for="for_name" class="col-sm-1 col-form-label">Nombres</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_name" placeholder="Nombres" >
            </div>
            <label for="for_region" class="col-sm-1 col-form-label">Región</label>
            <div class="col-sm-5">
                <select name="for_region" id="for_region" class="form-control">
                    <option value="male">Seleccione Región</option>
                    <option value="female">Región de Tarapacá</option>
                    <option value="other">Región de Antofagasta</option>
                    <option value="unknown">Región de Atacama</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="for_fathers_family" class="col-sm-1 col-form-label">Apellido Paterno</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_fathers_family" placeholder="Apellido Paterno">
            </div>
            <label for="for_city" class="col-sm-1 col-form-label">Ciudad</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_city" placeholder="Ciudad">
            </div>
        </div>
        <div class="form-group row">
            <label for="for_mothers_family" class="col-sm-1 col-form-label">Apellido Materno</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_mothers_family" placeholder="Apellido Materno">
            </div>
            <label for="for_city" class="col-sm-1 col-form-label">Comuna</label>
            <div class="col-sm-5">
                <select name="for_commune" id="for_commune" class="form-control">
                    <option value="male">Seleccione Comuna</option>
                    <option value="female">--</option>
                    <option value="other">Iquique</option>
                    <option value="unknown">Pica</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="for_gender" class="col-sm-1 col-form-label">Sexo</label>
            <div class="col-sm-5">
                <select name="gender" id="for_gender" class="form-control">
                    <option value="male">Masculino</option>
                    <option value="female">Femenino</option>
                    <option value="other">Otro</option>
                    <option value="unknown">Desconocido</option>
                </select>
            </div>
            <label for="for_telephone" class="col-sm-1 col-form-label">Teléfono</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_telephone" placeholder="Teléfono">
            </div>
        </div>
    </form>


@endsection
