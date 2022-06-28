@extends('layouts.app')

@section('title', 'Nuevo Usuario')

@section('content')

@include('parameters.nav')

<h3 class="mb-3">Nuevo Usuario</h3>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" class="form-horizontal" action="{{ route('users.store') }}" autocomplete="off">
    @csrf
    @method('POST')
    <div class="card mb-3">
        <div class="card-body">
            <div class="form-row">
                <fieldset class="form-group col-8 col-md-2">
                    <label for="for_run">Run *</label>
                    <input type="number" class="form-control" name="run" id="for_run"
                        required autocomplete="off">
                </fieldset>

                <fieldset class="form-group col-4 col-md-1">
                    <label for="for_dv">DV *</label>
                    <input type="text" class="form-control" name="dv" id="for_dv"
                        required>
                </fieldset>

                <fieldset class="form-group col-12 col-md-4">
                    <label for="for_name">Nombre y Apellido *</label>
                    <input type="text" class="form-control" name="name" id="for_name"
                        required autocomplete="off">
                </fieldset>

                <fieldset class="form-group col-12 col-md-3">
                    <label for="for_email">Email *</label>
                    <input type="email" class="form-control" name="email" id="for_email"
                        style="text-transform: lowercase;"
                        required autocomplete="new-text">
                </fieldset>

                <fieldset class="form-group col-12 col-md-2">
                    <label for="for_telephone">Telefono</label>
                    <input type="text" class="form-control" name="telephone"
                        id="for_telephone" placeholder="ej:+56912345678">
                </fieldset>
            
            </div>

            <div class="form-row">
                
                <fieldset class="form-group col-12 col-md-4">
                    <label for="for_password">Clave</label>
                    <input type="password" class="form-control" name="password" id="for_password"
                    autocomplete="new-password" placeholder="blanco=genera nueva al azar">
                </fieldset>

            </div>

            <div class="form-row">

                <fieldset class="form-group col-12 col-md-5">
                    <label for="for_my_establishment_id">Establecimiento al que pertenece*</label>
                    <select name="my_establishment_id" id="my_establishment_id" class="form-control" required>
                        <option value=""></option>
                        @foreach($establishments as $establishment)
                            <option value="{{ $establishment->id }}">{{ $establishment->alias }}</option>
                        @endforeach

                    </select>
                </fieldset>

                <fieldset class="form-group col-12 col-md-4">
                    <label for="for_function">Función que cumple</label>
                    <input type="text" class="form-control" name="function" id="for_function">
                </fieldset>

                <fieldset class="form-group col-12 col-md-3">
                    <label for="for_laboratory_id">Laboratorio asociado</label>
                    <select name="laboratory_id" id="for_laboratory_id" class="form-control">
                        <option value=""></option>
                        @foreach($laboratories as $lab)
                        <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                        @endforeach
                    </select>
                    <small id="laboratoryHelp" class="form-text text-muted">Sólo para ingresos en laboratorio</small>
                </fieldset>

                </div>

            <div class="form-row">
                <fieldset class="form-group col-12 col-md-12">
                    <label for="for_establishment_id">Establecimientos a los que tiene acceso *</label>
                    <select name="establishment_id[]" id="for_establishment_id" class="form-control selectpicker" data-live-search="true" multiple="" data-size="10" title="Seleccione..." multiple data-actions-box="true" required>
                        @foreach($establishments as $establishment)
                        <option value="{{ $establishment->id }}">{{ $establishment->alias }}</option>
                        @endforeach
                    </select>
                </fieldset>
            </div>
      </div>
  </div>

    @foreach($permissions as $permission)
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="permissions[]"
            value="{{ $permission->name }}">
        <label class="form-check-label">
            {{ $permission->name }}
        </label>
    </div>
    @endforeach

    <button type="submit" class="btn btn-primary mt-3">Guardar</button>


</form>



@endsection

@section('custom_js')
<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-select.min.css') }}">

<script src='{{asset("js/jquery.rut.chileno.js")}}'></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        //obtiene digito verificador
        $('input[name=run]').keyup(function(e) {
            var str = $("#for_run").val();
            $('#for_dv').val($.rut.dv(str));
        });
    });
</script>
<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('js/defaults-es_CL.min.js') }}"></script>

@endsection
