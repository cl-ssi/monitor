@extends('layouts.app')

@section('title', 'Contacto Pacientes')
@section('content')

<h3>Agregar Contacto de Pacientes</h3>

<br>
<form method="GET" class="form-horizontal" action="{{ route('patients.contacts.create', ['search'=>'search_true', 'id' => $id_patient]) }}">
<div class="input-group mb-sm-0">
    <div class="input-group-prepend">
        <span class="input-group-text">Búsqueda</span>
    </div>

    <input class="form-control" type="number" name="search" autocomplete="off" id="for_search" style="text-transform: uppercase;" placeholder="RUN (sin dígito verificador) o OTRA IDENTIFICACION" value="{{$request->search}}" required>

    <input class="form-control" type="text" name="dv" id="for_dv" style="text-transform: uppercase;" placeholder="DV" readonly hidden>

    <div class="input-group-append">
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
    </div>
</div>
</form>
alvaro {{ $id_patient }}

<hr>
<h5>Paciente:</h5>
@if($patients->isEmpty())
    @if($s == 'search_true')
        <div class="alert alert-danger" role="alert">
            El paciente consultado no se encuentra en nuestros registros.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@else
@foreach($patients as $patient)
    aaron {{ $patient->id }}
    <div class="form-row">
        <fieldset class="form-group col-md-4">
            <label for="for_register_at">Nombre</label>
            <input type="text" class="form-control" name="register_at" id="for_register_at" value="{{ $patient->name }}" style="text-transform: uppercase;" readonly>
        </fieldset>

        <fieldset class="form-group col-md-4">
            <label for="for_fathers_family">Apellido Paterno</label>
            <input type="text" class="form-control" value="{{ $patient->fathers_family }}" style="text-transform: uppercase;" readonly>
        </fieldset>

        <fieldset class="form-group col-md-4">
            <label for="for_mothers_family">Apellido Materno</label>
            <input type="text" class="form-control" value="{{ $patient->mothers_family }}" style="text-transform: uppercase;" readonly>
        </fieldset>
    </div>
    <div class="form-row">
        <fieldset class="form-group col-md-2">
            <label for="for_name">Género</label>
            <input type="text" class="form-control" value="{{ $patient->sexEsp }}" style="text-transform: uppercase;" readonly>
        </fieldset>

        <fieldset class="form-group col-md-2">
            <label for="for_fathers_family">Fecha Nac.</label>
            <input type="text" class="form-control" value="{{ ($patient->birthday)?$patient->birthday->format('d-m-Y'):'' }}" readonly>
        </fieldset>
    </div>
    <div class="form-row">
        <fieldset class="form-group col-md-2">
            <label for="for_mothers_family">Comuna: </label>
            <input type="text" class="form-control" value="{{ ($patient->demographic)?$patient->demographic->commune:'' }}" style="text-transform: uppercase;" readonly>
        </fieldset>

        <fieldset class="form-group col-md-4">
            <label for="for_mothers_family">Dirección: </label>
            <input type="text" class="form-control" value="{{ ($patient->demographic)?$patient->demographic->address:'' }}  {{($patient->demographic)?$patient->demographic->number:'' }}" style="text-transform: uppercase;" readonly>
        </fieldset>

        <fieldset class="form-group col-md-2">
            <label for="for_mothers_family">Teléfono: </label>
            <input type="text" class="form-control" value="{{ ($patient->demographic)?$patient->demographic->telephone:'' }}" readonly>
        </fieldset>

        <fieldset class="form-group col-md-4">
            <label for="for_mothers_family">E-mail: </label>
            <input type="text" class="form-control" value="{{ ($patient->demographic)?$patient->demographic->email:'' }}" style="text-transform: uppercase;" readonly>
        </fieldset>
    </div>
    <hr>
@endforeach

<div class="card mb-3">
    <div class="card-body">
        <h5>Ingreso los datos del contacto:</h5>

        <form method="POST" class="form-horizontal" action="{{ route('patients.contacts.store') }}">
            @csrf
            @method('POST')
            <div class="form-row">
              <fieldset class="form-group col-6">
                  <label for="for_comment">Obervación</label>
                  <textarea class="form-control" name="comment"  id="for_comment" rows="1"></textarea>
              </fieldset>

              <fieldset class="form-group col-md-6">
                  <label for="for_register_at">Parentesco</label>
                  <select class="form-control selectpicker" name="relationship" id="for_relationship" title="Seleccione..." required>
                      <option value="father">Padre</option>
                      <option value="mother">Madre</option>
                      <option value="brother">hijo/a</option>
                      <option value="brother">Hermano/a</option>
                      <option value="contact">Contacto/a</option>
                  </select>
              </fieldset>

              <fieldset class="form-group col-md-4" hidden>
                  <input type="text" class="form-control" name="patient_id" id="for_patient_id" value="{{ $id_patient }}">
              </fieldset>

              <fieldset class="form-group col-md-4" hidden>
                  <input type="text" class="form-control" name="contact_id" id="for_contact_id" value="{{ $patient->id }}">
              </fieldset>

              <fieldset class="form-group col-md-4" hidden>
                  <input type="text" class="form-control" name="user_id" id="for_user_id" value="{{ Auth::id() }}">
              </fieldset>

              <hr>
              <button type="submit" class="btn btn-primary float-right">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endif

@endsection

@section('custom_js')
<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-select.min.css') }}">

<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('js/defaults-es_CL.min.js') }}"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<script src='{{asset("js/jquery.rut.chileno.js")}}'></script>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        //obtiene digito verificador
        $('input[name=search]').keyup(function(e) {
            var str = $("#for_search").val();
            $('#for_dv').val($.rut.dv(str));
        });
    });
</script>

<script type="text/javascript">
$(document).ready(function(){
    // $("main").removeClass("container");

    $("#inputSearch").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#tablePatients tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>

@endsection
