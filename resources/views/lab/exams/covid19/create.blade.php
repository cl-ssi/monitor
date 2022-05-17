@extends('layouts.app')

@section('title', 'Agregar nueva muestra')

@section('content')
<h3 class="mb-3">Agregar nueva muestra</h3>

<form method="POST" class="form-horizontal" action="{{ route('lab.exams.covid19.store') }}">
    @csrf
    @method('POST')

    <div class="form-row">
        <fieldset class="form-group col-md-2">
            <label for="for_run">Run (sin digito)</label>
            <input type="number" max="80000000" class="form-control" name="run" id="for_run">
        </fieldset>

        <fieldset class="form-group col-md-1">
            <label for="for_dv">Digito</label>
            <input type="text" class="form-control" name="dv" id="for_dv" readonly>
        </fieldset>

        <fieldset class="form-group col-1 col-md-1">
            <label for="">&nbsp;</label>
            <button type="button" id="btn_fonasa" class="btn btn-outline-success">Fonasa&nbsp;</button>
        </fieldset>

        <fieldset class="form-group col-1 col-md-1">
            <label for="">&nbsp;</label>
            <span class="form-control-plaintext"> </span>
        </fieldset>

        <fieldset class="form-group col-md-2">
            <label for="for_other_identification">Otra identificación</label>
            <input type="text" class="form-control" name="other_identification"
                id="for_other_identification" placeholder="Extranjeros">
        </fieldset>

    </div>
    <div class="form-row">

        <fieldset class="form-group col-md-3">
            <label for="for_name">Nombre *</label>
            <input type="text" class="form-control" name="name" id="for_name"
                required>
        </fieldset>

        <fieldset class="form-group col-md-2">
            <label for="for_fathers_family">Apellido Paterno *</label>
            <input type="text" class="form-control" name="fathers_family"
                id="for_fathers_family" required>
        </fieldset>

        <fieldset class="form-group col-md-2">
            <label for="for_mothers_family">Apellido Materno</label>
            <input type="text" class="form-control" name="mothers_family"
                id="for_mothers_family">
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_gender">Género *</label>
            <select name="gender" id="for_gender" class="form-control" required>
                <option value=""></option>
                <option value="male">Masculino</option>
                <option value="female">Femenino</option>
                <option value="other">Otro</option>
                <option value="unknown">Desconocido</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_birthday">Fecha Nacimiento *</label>
            <input type="date" class="form-control" id="for_birthday"
                name="birthday" required>
        </fieldset>

    </div>

    <hr>

    <div class="form-row">

        <fieldset class="form-group col-md-3">
            <label for="for_email">Email</label>
            <input type="text" class="form-control" name="email" id="for_email">
        </fieldset>

        <fieldset class="form-group col-md-2">
            <label for="for_telephone">Telefono</label>
            <input type="text" class="form-control" name="telephone" id="for_telephone">
        </fieldset>

        <fieldset class="form-group col-md-4">
            <label for="for_address">Dirección</label>
            <input type="text" class="form-control" name="address" id="for_address">
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="regiones_demo">Región *</label>
            <select class="form-control" name="region_id_demo" id="regiones_demo">
                <option>Seleccione Región</option>
                @foreach ($regions as $key => $region)
                    <option value="{{$region->id}}" {{(old('region_id') == $region->id) ? 'selected' : '' }} >{{$region->name}}</option>
                @endforeach
            </select>
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_commune_id">Comuna *</label>
            <select class="form-control" name="commune_id" id="for_commune_id" required></select>
        </fieldset>

    </div>

    <hr>

    <div class="form-row">

        <fieldset class="form-group col-12 col-md-3">
            <label for="regiones">Región Origen *</label>
            <select class="form-control" name="region_id" id="regiones">
                <option>Seleccione Región</option>
                @foreach ($regions as $key => $region)
                    <option value="{{$region->id}}" {{(old('region_id') == $region->id) ? 'selected' : '' }} >{{$region->name}}</option>
                @endforeach
            </select>
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_origin_commune">Comuna Origen *</label>
            <select class="form-control" name="origin_commune" id="for_origin_commune" required></select>
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_establishment_id">Establecimiento Origen *</label>
            <select class="form-control" name="establishment_id" id="for_establishment_id" required></select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-3">
            <label for="for_sample_type">Tipo de Muestra *</label>
            <select name="sample_type" id="for_sample_type" class="form-control" required>
                <option value=""></option>
                <option value="TÓRULAS NASOFARÍNGEAS">TORULAS NASOFARINGEAS</option>
                <option value="ESPUTO">ESPUTO</option>
                <option value="TÓRULAS NASOFARÍNGEAS/ESPUTO">TÓRULAS NASOFARÍNGEAS/ESPUTO</option>
                <option value="ASPIRADO NASOFARÍNGEO">ASPIRADO NASOFARÍNGEO</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-3">
            <label for="for_sample_at">Fecha de muestra *</label>
            <input type="datetime-local" class="form-control" name="sample_at"
                id="for_sample_at" required value="{{ date('Y-m-d\TH:i:s') }}">
        </fieldset>

        <fieldset class="form-group col-8 col-md-2">
            <label for="for_run_medic">Run Médico SIN DV</label>
            <input type="text" class="form-control" name="run_medic"
                   id="for_run_medic" >
        </fieldset>

        <fieldset class="form-group col-4 col-md-1">
            <label for="for_run_medic_dv">DV</label>
            <input type="text" class="form-control" id="for_run_medic_dv" name="run_medic_dv" readonly>
        </fieldset>

        <fieldset class="form-group col-8 col-md-2">
            <label for="for_run_responsible">Run Responsable SIN DV *</label>
            <input type="text" class="form-control" name="run_responsible"
                   id="for_run_responsible" required>
        </fieldset>

        <fieldset class="form-group col-4 col-md-1">
            <label for="for_run_responsible_dv">DV *</label>
            <input type="text" class="form-control" id="for_run_responsible_dv" name="run_responsible_dv" readonly>
        </fieldset>


    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>

</form>

@endsection

@section('custom_js')
<script src='{{asset("js/jquery.rut.chileno.js")}}'></script>

<script type="text/javascript">
jQuery(document).ready(function($){
    //obtiene digito verificador
    $('input[name=run]').keyup(function(e) {
        var str = $("#for_run").val();
        $('#for_dv').val($.rut.dv(str));
    });

    //obtiene digito verificador
    $('input[name=run_medic]').keyup(function(e) {
        var str = $("#for_run_medic").val();
        $('#for_run_medic_dv').val($.rut.dv(str));
    });

    //obtiene digito verificador
    $('input[name=run_responsible]').keyup(function(e) {
        var str = $("#for_run_responsible").val();
        $('#for_run_responsible_dv').val($.rut.dv(str));
    });

    $('#btn_fonasa').click(function() {
	    var btn = $(this);
	    btn.prop('disabled',true);

        var run = $("#for_run").val();
        var dv  = $("#for_dv").val();
        var url = '{{route('webservices.fonasa')}}/?run='+run+'&dv='+dv;

        $.getJSON(url, function(data) {
            if(data){
                document.getElementById("for_name").value = data.name;
                document.getElementById("for_fathers_family").value = data.fathers_family;
                document.getElementById("for_mothers_family").value = data.mothers_family;
                document.getElementById("for_gender").value = data.gender;
                document.getElementById("for_birthday").value = data.birthday;
            } else {
                document.getElementById("for_name").value = "";
                document.getElementById("for_fathers_family").value = "";
                document.getElementById("for_mothers_family").value = "";
                document.getElementById("for_gender").value = "";
                document.getElementById("for_birthday").value = "";
            }
	}).done(function() {
            btn.prop('disabled',false);
        });
    });

    // cuando cambia region
    jQuery('#regiones_demo').change(function () {
        var valorRegion = jQuery(this).val();
        var htmlComuna = '<option value="sin-comuna">Seleccione comuna</option><option value="sin-comuna">--</option>';
        @foreach ($communes as $key => $commune)
        if (valorRegion == '{{$commune->region_id}}') {
            htmlComuna = htmlComuna + '<option value="' + '{{$commune->id}}' + '">' + '{{$commune->name}}' + '</option>';
        }
        @endforeach
        jQuery('#for_commune_id').html(htmlComuna);
    });

    // cuando cambia region origen
    jQuery('#regiones').change(function () {
        var valorRegion = jQuery(this).val();
        var htmlComuna = '<option value="sin-comuna">Seleccione comuna</option><option value="sin-comuna">--</option>';
        @foreach ($communes as $key => $commune)
        if (valorRegion == '{{$commune->region_id}}') {
            htmlComuna = htmlComuna + '<option value="' + '{{$commune->id}}' + '">' + '{{$commune->name}}' + '</option>';
        }
        @endforeach
        jQuery('#for_origin_commune').html(htmlComuna);
    });

    // cuando cambia comuna origen
    jQuery('#for_origin_commune').change(function () {
        var valorComuna = jQuery(this).val();
        var htmlEstablecimiento = '<option value="sin-comuna">Seleccione establecimiento</option><option value="sin-establecimiento">--</option>';
        @foreach ($establishments as $key => $establishment)
        if (valorComuna == '{{$establishment->commune_id}}') {
            htmlEstablecimiento = htmlEstablecimiento + '<option value="' + '{{$establishment->id}}' + '">' + '{{$establishment->name}}' + '</option>';
        }
        @endforeach
        jQuery('#for_establishment_id').html(htmlEstablecimiento);
    });

});
</script>
@endsection
