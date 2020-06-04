@extends('layouts.app')

@section('title', 'Agregar Test IgG - IgM')
@section('content')

<h3>Test IgG - IgM</h3>

<hr>

<h5>Paciente:</h5>

<h5>Ingreso de resultados:</h5>

<form method="POST" class="form-horizontal" action="{{ route('lab.inmuno_tests.update', $inmunoTest) }}">
  @csrf
  @method('PUT')
  <div class="form-row">
    <fieldset class="form-group col-md-3">
        <label for="for_register_at">Fecha de Examen</label>
        <input type="datetime-local" class="form-control" name="register_at" id="for_register_at" value="{{ $inmunoTest->register_at->format('Y-m-d\TH:i:s') }}">
    </fieldset>

    <fieldset class="form-group col-md-3">
        <label for="for_register_at">IgG Valor</label>
        <select class="form-control selectpicker" name="igg_value" id="for_igg_value" title="Seleccione..." required>
            <option value="positive" {{ ($inmunoTest->igg_value == 'positive')?'selected':'' }}>Positivo</option>
            <option value="negative" {{ ($inmunoTest->igg_value == 'negative')?'selected':'' }}>Negativo</option>
            <option value="weak" {{ ($inmunoTest->igg_value == 'weak')?'selected':'' }}>Débil</option>
        </select>
    </fieldset>

    <fieldset class="form-group col-md-3">
        <label for="for_register_at">IgM Valor</label>
        <select class="form-control selectpicker" name="igm_value" id="for_igm_value" title="Seleccione..." required>
            <option value="positive" {{ ($inmunoTest->igm_value == 'positive')?'selected':'' }}>Positivo</option>
            <option value="negative" {{ ($inmunoTest->igm_value == 'negative')?'selected':'' }}>Negativo</option>
            <option value="weak" {{ ($inmunoTest->igm_value == 'weak')?'selected':'' }}>Débil</option>
        </select>
    </fieldset>

    <fieldset class="form-group col-md-3">
        <label for="for_control">Control</label>
        <select class="form-control selectpicker" name="control" id="for_control" title="Seleccione..." required>
            <option value="yes" {{ ($inmunoTest->control == 'yes')?'selected':'' }}>Si</option>
            <option value="no" {{ ($inmunoTest->control == 'no')?'selected':'' }}>No</option>
        </select>
    </fieldset>

    <hr>
    <button type="submit" class="btn btn-primary float-right">Guardar</button>
  </div>
</form>


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
