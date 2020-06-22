@extends('layouts.app')

@section('title', 'Nueva sospecha')

@section('content')
<h3 class="mb-3">Nueva sospecha</h3>

<form method="POST" class="form-horizontal" action="{{ route('lab.suspect_cases.store_admission') }}" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="form-row">

        <fieldset class="form-group col-8 col-md-2">
            <label for="for_run">Run SIN DIGITO VERIF.</label>
            <input type="hidden" class="form-control" id="for_id" name="id">
            <input type="number" class="form-control" id="for_run" name="run">
        </fieldset>

        <fieldset class="form-group col-4 col-md-1">
            <label for="for_dv">Digito</label>
            <input type="text" class="form-control" id="for_dv" name="dv" readonly>
        </fieldset>

        <fieldset class="form-group col-1 col-md-1">
            <label for="">&nbsp;</label>
            <button type="button" id="btn_fonasa" class="btn btn-outline-success">Fonasa&nbsp;</button>
        </fieldset>

        <fieldset class="form-group col-12 col-md-2">
            <label for="for_other_identification">Otra identificación</label>
            <input type="text" class="form-control" id="for_other_identification"
                placeholder="Extranjeros sin run" name="other_identification">
        </fieldset>

        {{-- <fieldset class="form-group col-6 col-md-2">
            <label for="for_country_id">País</label>
            <select name="country_id" id="for_country_id" class="form-control" readonly>
              @foreach ($countries as $key => $countrie)
                <option value="{{$countrie->id}}" {{ ($countrie->id == 41)?'selected':'' }}>{{$countrie->name}}</option>
              @endforeach
            </select>
        </fieldset> --}}

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_gender">Genero</label>
            <select name="gender" id="for_gender" class="form-control">
                <option value="male">Masculino</option>
                <option value="female">Femenino</option>
                <option value="other">Otro</option>
                <option value="unknown">Desconocido</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-3">
            <label for="for_birthday">Fecha Nacimiento *</label>
            <input type="date" class="form-control" id="for_birthday"
                name="birthday" required>
        </fieldset>

        <fieldset class="form-group col-6 col-md-1">
            <label for="for_age">Edad</label>
            <input type="number" class="form-control" id="for_age" min="0" name="age">
        </fieldset>


    </div>

    <div class="form-row">
        <fieldset class="form-group col-12 col-md-4">
            <label for="for_name">Nombres *</label>
            <input type="text" class="form-control" id="for_name" name="name"
                style="text-transform: uppercase;" required>
        </fieldset>

        <fieldset class="form-group col-12 col-md-4">
            <label for="for_fathers_family">Apellido Paterno *</label>
            <input type="text" class="form-control" id="for_fathers_family"
                name="fathers_family" style="text-transform: uppercase;" required>
        </fieldset>

        <fieldset class="form-group col-12 col-md-4">
            <label for="for_mothers_family">Apellido Materno</label>
            <input type="text" class="form-control" id="for_mothers_family"
                name="mothers_family" style="text-transform: uppercase;">
        </fieldset>


    </div>

    <hr>
    @include('patients.demographic.create')
    <hr>

    <div class="form-row">

        <fieldset class="form-group col-5 col-md-3">
            <label for="for_sample_at">Fecha Muestra *</label>
            <input type="datetime-local" class="form-control" id="for_sample_at"
                name="sample_at" value="{{ date('Y-m-d\TH:i:s') }}" required min="{{ date('Y-m-d\TH:i:s', strtotime("-2 week")) }}" max="{{ date('Y-m-d\TH:i:s') }}">
        </fieldset>

        <fieldset class="form-group col-7 col-md-3">
            <label for="for_sample_type">Tipo de Muestra *</label>
            <select name="sample_type" id="for_sample_type" class="form-control" required>
                <option value=""></option>
                <option value="TÓRULAS NASOFARÍNGEAS">TORULAS NASOFARINGEAS</option>
                <option value="ESPUTO">ESPUTO</option>
                <option value="TÓRULAS NASOFARÍNGEAS/ESPUTO">TÓRULAS NASOFARÍNGEAS/ESPUTO</option>
                <option value="ASPIRADO NASOFARÍNGEO">ASPIRADO NASOFARÍNGEO</option>
                {{-- <option value="LAVADO BRONCOALVEOLAR">LAVADO BRONCOALVEOLAR</option>
                <option value="ASPIRADO TRAQUEAL">ASPIRADO TRAQUEAL</option>
                <option value="MUESTRA SANGUÍNEA">MUESTRA SANGUÍNEA</option>
                <option value="TEJIDO PULMONAR">TEJIDO PULMONAR</option> --}}
            </select>
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_establishment_id">Establecimiento *</label>
            <select name="establishment_id" id="for_establishment_id" class="form-control" required>
                <option value="">Seleccionar Establecimiento</option>
                @foreach($establishments as $establishment)
                    <option value="{{ $establishment->id }}">{{ $establishment->alias }}</option>
                @endforeach
            </select>
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_origin">Estab. Detalle (Opcional)</label>
            <select name="origin" id="for_origin" class="form-control">
                <option value=""></option>
                @foreach($sampleOrigins as $sampleOrigin)
                    <option value="{{ $sampleOrigin->name }}">{{ $sampleOrigin->alias }}</option>
                @endforeach
            </select>
        </fieldset>

    </div>

    <div class="form-row">

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_functionary">Funcionario de Salud</label>
            <select name="functionary" id="for_functionary" class="form-control">
                <option value=""></option>
                <option value="0">No</option>
                <option value="1">Si</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-1">
            <label for="for_symptoms">Sintomas</label>
            <select name="symptoms" id="for_symptoms" class="form-control">
                <option value=""></option>
                <option value="Si">Si</option>
                <option value="No">No</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-5 col-md-3">
            <label for="for_symptoms_at">Fecha Inicio de Sintomas</label>
            <input type="datetime-local" class="form-control" id="for_symptoms_at"
                name="symptoms_at" min="{{ date('Y-m-d\TH:i', strtotime("-4 week")) }}" max="{{ date('Y-m-d\TH:i:s') }}">
        </fieldset>

        <fieldset class="form-group col-6 col-md-1">
            <label for="for_gestation">Gestante *</label>
            <select name="gestation" id="for_gestation" class="form-control" required>
                <option value=""></option>
                <option value="0">No</option>
                <option value="1">Si</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_gestation_week">Semanas de gestación</label>
            <input type="text" class="form-control" name="gestation_week"
                id="for_gestation_week">
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_close_contact">Contacto estrecho</label>
            <select name="close_contact" id="for_close_contact" class="form-control">
                <option value=""></option>
                <option value="0">No</option>
                <option value="1">Si</option>
            </select>
        </fieldset>

        {{-- <!--fieldset class="form-group col-4 col-md-2">
            <label for="for_discharge_test">Test de salida</label>
            <select name="discharge_test" id="for_discharge_test" class="form-control">
                <option value=""></option>
                <option value="0">No</option>
                <option value="1">Si</option>
            </select>
        </fieldset--> --}}


        <!--fieldset class="form-group col-8 col-md-4">
            <label for="for_status">Estado</label>
            <select name="status" id="for_status" class="form-control">
                <option value=""></option>
                <option value="Alta">Alta</option>
                <option value="Ambulatorio">Ambulatorio (domiciliario)</option>
                <option value="Fallecido">Fallecido</option>
                <option value="Fugado">Fugado</option>
                <option value="Hospitalizado Básico">Hospitalizado Básico</option>
                <option value="Hospitalizado Medio">Hospitalizado Medio</option>
                <option value="Hospitalizado UTI">Hospitalizado UTI</option>
                <option value="Hospitalizado UCI">Hospitalizado UCI</option>
                <option value="Hospitalizado UCI (Ventilador)">Hospitalizado UCI (Ventilador)</option>
                <option value="Residencia Sanitaria">Residencia Sanitaria</option>
            </select>
        </fieldset-->

    </div>

    <hr>


    <div class="form-row">

        <fieldset class="form-group col-12 col-md-4">
            <label for="for_observation">Observación</label>
            <input type="text" class="form-control" name="observation"
                id="for_observation">
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_paho_flu">PAHO FLU</label>
            <input type="number" class="form-control" name="paho_flu"
                id="for_paho_flu">
        </fieldset>

        {{-- <fieldset class="form-group col-6 col-md-2">
            <label for="for_run_medic">Run Médico Solicitante *</label>
            <input type="text" class="form-control" name="run_medic" id="for_run_medic"
                required placeholder="Ej: 12345678-9">
        </fieldset> --}}

        <fieldset class="form-group col-8 col-md-2">
            <label for="for_run_medic_s_dv">Run Médico SIN DV</label>
            <input type="number" class="form-control" id="for_run_medic_s_dv" name="run_medic_s_dv">
        </fieldset>

        <fieldset class="form-group col-4 col-md-1">
            <label for="for_run_medic_dv">DV</label>
            <input type="text" class="form-control" id="for_run_medic_dv" name="run_medic_dv" readonly>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_epivigila">Epivigila *</label>
            <input type="number" class="form-control" id="for_epivigila"
                name="epivigila" min="0" required>
        </fieldset>

    </div>

    <hr>

    <button type="submit" class="btn btn-primary">Guardar</button>

    <a class="btn btn-outline-secondary" href="{{ route('lab.suspect_cases.index') }}">
        Cancelar
    </a>
</form>

@endsection

@section('custom_js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src='{{asset("js/jquery.rut.chileno.js")}}'></script>
<script type="text/javascript">
jQuery(document).ready(function($){
    //obtiene digito verificador
    $('input[name=run]').keyup(function(e) {
        var str = $("#for_run").val();
        $('#for_dv').val($.rut.dv(str));
    });

    $('input[name=run_medic_s_dv]').keyup(function(e) {
        var str = $("#for_run_medic_s_dv").val();
        $('#for_run_medic_dv').val($.rut.dv(str));
    });

$('input[name=run]').change(function() {
    var str = $("#for_run").val();
    $.get('{{ route('patients.get')}}/'+str, function(data) {
        if(data){
            document.getElementById("for_id").value = data.id;
            document.getElementById("for_other_identification").value = data.other_identification;
            document.getElementById("for_gender").value = data.gender;
            document.getElementById("for_birthday").value = data.birthday;
            document.getElementById("for_name").value = data.name;
            document.getElementById("for_fathers_family").value = data.fathers_family;
            document.getElementById("for_mothers_family").value = data.mothers_family;
            document.getElementById("for_status").value = data.status;
        } else {
            document.getElementById("for_id").value = "";
            document.getElementById("for_other_identification").value = "";
            document.getElementById("for_gender").value = "";
            document.getElementById("for_birthday").value = "";
            document.getElementById("for_name").value = "";
            document.getElementById("for_fathers_family").value = "";
            document.getElementById("for_mothers_family").value = "";
        }
    });
});

$('input[name=other_identification]').change(function() {
    var str = $("#for_other_identification").val();
    $.get('{{ route('patients.get')}}/'+str, function(data) {
        if(data){
            document.getElementById("for_id").value = data.id;
            // document.getElementById("for_other_identification").value = data.other_identification;
            document.getElementById("for_gender").value = data.gender;
            document.getElementById("for_birthday").value = data.birthday;
            document.getElementById("for_name").value = data.name;
            document.getElementById("for_fathers_family").value = data.fathers_family;
            document.getElementById("for_mothers_family").value = data.mothers_family;
            document.getElementById("for_status").value = data.status;
        } else {
            document.getElementById("for_id").value = "";
            // document.getElementById("for_other_identification").value = "";
            document.getElementById("for_gender").value = "";
            document.getElementById("for_birthday").value = "";
            document.getElementById("for_name").value = "";
            document.getElementById("for_fathers_family").value = "";
            document.getElementById("for_mothers_family").value = "";
        }
    });
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
            // document.getElementById("for_gender").value = data.gender;
            document.getElementById("for_birthday").value = data.birthday;
        } else {
            document.getElementById("for_name").value = "";
            document.getElementById("for_fathers_family").value = "";
            document.getElementById("for_mothers_family").value = "";
            // document.getElementById("for_gender").value = "";
            document.getElementById("for_birthday").value = "";
        }
}).done(function() {
        btn.prop('disabled',false);
    });
});


});

</script>

<script src="https://js.api.here.com/v3/3.1/mapsjs-core.js" type="text/javascript" charset="utf-8"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-service.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">

jQuery(document).ready(function () {

    /* caso cuando se cambie manualmente */
	jQuery('#regiones').change(function () {
		var iRegiones = 0;
		var valorRegion = jQuery(this).val();
		var htmlComuna = '<option value="">Seleccione comuna</option><option value="sin-comuna">--</option>';
        @foreach ($communes as $key => $commune)
            if (valorRegion == '{{$commune->region_id}}') {
                htmlComuna = htmlComuna + '<option value="' + '{{$commune->id}}' + '">' + '{{$commune->name}}' + '</option>';
            }
        @endforeach
		jQuery('#comunas').html(htmlComuna);
	});

    //obtener coordenadas y establecimientos
    jQuery('#comunas').change(function () {

    //coordenadas
    // Instantiate a map and platform object:
    var platform = new H.service.Platform({
      'apikey': '{{ env('API_KEY_HERE') }}'
    });

    var address = jQuery('#for_address').val();
    var number = jQuery('#for_number').val();
    var regiones = $("#regiones option:selected").html();
    var comunas = $("#comunas option:selected").html();

    if (address != "" && number != "" && regiones != "Seleccione región" && comunas != "Seleccione comuna") {
      // Create the parameters for the geocoding request:
        var geocodingParams = {
            searchText: address + ' ' + number + ', ' + comunas + ', chile'
          };console.log(geocodingParams);

      // Define a callback function to process the geocoding response:

      jQuery('#for_latitude').val("");
      jQuery('#for_longitude').val("");
      var onResult = function(result) {
        console.log(result);
        var locations = result.Response.View[0].Result;

        // Add a marker for each location found
        for (i = 0;  i < locations.length; i++) {
          //alert(locations[i].Location.DisplayPosition.Latitude);
          jQuery('#for_latitude').val(locations[i].Location.DisplayPosition.Latitude);
          jQuery('#for_longitude').val(locations[i].Location.DisplayPosition.Longitude);
        }
      };

      // Get an instance of the geocoding service:
      var geocoder = platform.getGeocodingService();

      // Error
      geocoder.geocode(geocodingParams, onResult, function(e) {
        alert(e);
      });
    }

  });

  // jQuery('#for_run').click(function () {
  //   $("#for_country_id").val(41);
  //   $('#for_country_id').attr('readonly', true);
  // });
  //
  // jQuery('#for_other_identification').click(function () {
  //   $("#for_country_id").val(1);
  //   $('#for_country_id').attr('readonly', false);
  // });

});
</script>

@endsection
