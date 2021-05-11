@extends('layouts.app')

@section('title', 'Nueva sospecha')

@section('content')
    <div class="row">
        <div class="col-4">
            <h3 class="mb-3">Nueva sospecha</h3>
        </div>
        <div class="col-4"></div>
        <div class="col-4 text-right ">
            <h6>Laboratorio: {{\Illuminate\Support\Facades\Auth::user()->laboratory->alias}}</h6>
        </div>
    </div>


@if ($errors->any())
    <div class="alert alert-warning">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" class="form-horizontal" action="{{ route('lab.suspect_cases.store_admission') }}" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="form-row">

        <input type="hidden" name="id_laboratory" id="for_id_laboratory" value="{{Auth::user()->laboratory->id_openagora}}">

        <fieldset class="form-group col-8 col-md-2">
            <label for="for_run">Run SIN DIGITO VERIF.</label>
            <input type="hidden" class="form-control" id="for_id" name="id" value="{{old('id')}}">
            <input type="number" max="50000000" class="form-control" id="for_run" name="run" value="{{old('run')}}">
        </fieldset>

        <fieldset class="form-group col-4 col-md-1">
            <label for="for_dv">Digito</label>
            <input type="text" class="form-control" id="for_dv" name="dv" readonly value="{{old('dv')}}">
        </fieldset>

        <fieldset class="form-group col-1 col-md-1">
            <label for="">&nbsp;</label>
            <button type="button" id="btn_fonasa" class="btn btn-outline-success form-control">Fonasa&nbsp;</button>
        </fieldset>

        <fieldset class="form-group col-12 col-md-2">
            <label for="for_other_identification">Otra identificación</label>
            <input type="text" class="form-control" id="for_other_identification" value="{{old('other_identification')}}"
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
            <label for="for_gender">Género *</label>
            <select name="gender" id="for_gender" class="form-control" required>
                <option disabled selected value></option>
                <option value="male" {{(old('gender') == 'male') ? 'selected' : '' }} >Masculino</option>
                <option value="female" {{(old('gender') == 'female') ? 'selected' : '' }} >Femenino</option>
                <option value="other" {{(old('gender') == 'other') ? 'selected' : '' }} >Otro</option>
                <option value="unknown" {{(old('gender') == 'unknown') ? 'selected' : '' }} >Desconocido</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-3">
            <label for="for_birthday">Fecha Nacimiento *</label>
            <input type="date" class="form-control" id="for_birthday" value="{{old('birthday')}}" min="1900-01-01" max="{{Carbon\Carbon::now()->toDateString()}}"
                name="birthday" required>
        </fieldset>

        <fieldset class="form-group col-6 col-md-1">
            <label for="for_age">Edad</label>
            <input type="number" class="form-control" id="for_age" min="0" name="age" value="{{old('age')}}">
        </fieldset>


    </div>

    <div class="form-row">
        <fieldset class="form-group col-12 col-md-4">
            <label for="for_name">Nombres *</label>
            <input type="text" class="form-control" id="for_name" name="name" value="{{old('name')}}"
                style="text-transform: uppercase;" required>
        </fieldset>

        <fieldset class="form-group col-12 col-md-4">
            <label for="for_fathers_family">Apellido Paterno *</label>
            <input type="text" class="form-control" id="for_fathers_family" value="{{old('fathers_family')}}"
                name="fathers_family" style="text-transform: uppercase;" required>
        </fieldset>

        <fieldset class="form-group col-12 col-md-4">
            <label for="for_mothers_family">Apellido Materno</label>
            <input type="text" class="form-control" id="for_mothers_family" value="{{old('mothers_family')}}"
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
                <option value="" {{(old('sample_type') == '') ? 'selected' : '' }} ></option>
                <option value="TÓRULAS NASOFARÍNGEAS" {{(old('sample_type') == 'TÓRULAS NASOFARÍNGEAS') ? 'selected' : '' }}>TORULAS NASOFARINGEAS</option>
                <option value="ESPUTO" {{(old('sample_type') == 'ESPUTO') ? 'selected' : '' }}>ESPUTO</option>
                {{-- <option value="TÓRULAS NASOFARÍNGEAS/ESPUTO">TÓRULAS NASOFARÍNGEAS/ESPUTO</option> --}}
                <option value="ASPIRADO NASOFARÍNGEO" {{(old('sample_type') == 'ASPIRADO NASOFARÍNGEO') ? 'selected' : '' }}>ASPIRADO NASOFARÍNGEO</option>
                <option value="LAVADO BRONCOALVEOLAR" {{(old('sample_type') == 'LAVADO BRONCOALVEOLAR') ? 'selected' : '' }}>LAVADO BRONCOALVEOLAR</option>
                <option value="ASPIRADO TRAQUEAL" {{(old('sample_type') == 'ASPIRADO TRAQUEAL') ? 'selected' : '' }}>ASPIRADO TRAQUEAL</option>
                <option value="MUESTRA SANGUÍNEA" {{(old('sample_type') == 'MUESTRA SANGUÍNEA') ? 'selected' : '' }}>MUESTRA SANGUÍNEA</option>
                <option value="TEJIDO PULMONAR" {{(old('sample_type') == 'TEJIDO PULMONAR') ? 'selected' : '' }}>TEJIDO PULMONAR</option>
                <option value="SALIVA" {{(old('sample_type') == 'SALIVA') ? 'selected' : '' }}>SALIVA</option>
                <option value="OTRO" {{(old('sample_type') == 'OTRO') ? 'selected' : '' }}>OTRO</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_establishment_id">Establecimiento *</label>
            <select name="establishment_id" id="for_establishment_id" class="form-control" required>
                <option value="">Seleccionar Establecimiento</option>
                @foreach($establishmentsusers as $establishmentsusers)
                    <option value="{{ $establishmentsusers->establishment->id }}" {{(old('establishment_id') == $establishmentsusers->establishment->id) ? 'selected' : '' }} >{{ $establishmentsusers->establishment->alias }}</option>
                @endforeach
            </select>
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_origin">Estab. Detalle (Opcional)</label>
            <select name="origin" id="for_origin" class="form-control">
                <option value=""></option>
                @foreach($sampleOrigins as $sampleOrigin)
                    <option value="{{ $sampleOrigin->name }}" {{(old('origin') == $sampleOrigin->name) ? 'selected' : '' }} >{{ $sampleOrigin->alias }}</option>
                @endforeach
            </select>
        </fieldset>

    </div>

    <div class="form-row align-items-end">
<!-------------------------------->
        <fieldset class="form-group col-6 col-md-2">
            <label for="for_functionary">Funcionario de Salud</label>
            <select name="functionary" id="for_functionary" class="form-control">
                <option value="" {{(old('functionary') == '') ? 'selected' : '' }}></option>
                <option value="0" {{(old('functionary') == '0') ? 'selected' : '' }}>No</option>
                <option value="1" {{(old('functionary') == '1') ? 'selected' : '' }}>Si</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-1">
            <label for="for_symptoms">Sintomas</label>
            <select name="symptoms" id="for_symptoms" class="form-control">
                <option value="" {{(old('symptoms') == '') ? 'selected' : '' }}></option>
                <option value="1" {{(old('symptoms') == '1') ? 'selected' : '' }}>Si</option>
                <option value="0" {{(old('symptoms') == '0') ? 'selected' : '' }}>No</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-8 col-md-3">
            <label for="for_symptoms_at">Fecha Inicio de Sintomas</label>
            <input type="datetime-local" class="form-control" id="for_symptoms_at" value="{{old('symptoms_at')}}"
                name="symptoms_at" min="{{ date('Y-m-d\TH:i', strtotime("-4 week")) }}" max="{{ date('Y-m-d\TH:i:s') }}">
        </fieldset>

        <fieldset class="form-group col-4 col-md-1">
            <label for="for_gestation">Gestante *</label>
            <select name="gestation" id="for_gestation" class="form-control" required>
                <option value="" {{(old('gestation') == '') ? 'selected' : '' }}></option>
                <option value="0" {{(old('gestation') == '0') ? 'selected' : '' }}>No</option>
                <option value="1" {{(old('gestation') == '1') ? 'selected' : '' }}>Si</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-1">
            <label for="for_gestation_week">Semanas de gestación</label>
            <input type="number" class="form-control" name="gestation_week" value="{{old('gestation_week')}}"
                id="for_gestation_week">
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_close_contact">Contacto estrecho</label>
            <select name="close_contact" id="for_close_contact" class="form-control">
                <option value="" {{(old('close_contact') == '') ? 'selected' : '' }}></option>
                <option value="0" {{(old('close_contact') == '0') ? 'selected' : '' }}>No</option>
                <option value="1" {{(old('close_contact') == '1') ? 'selected' : '' }}>Si</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-4 col-md-2">
            <label for="for_case_type">Tipo de caso*</label>
            <select name="case_type" id="for_case_type" class="form-control" required>
                <option value="" {{(old('case_type') == '') ? 'selected' : '' }}></option>
                <option value="Atención médica" {{(old('case_type') == 'Atención médica') ? 'selected' : '' }}>Atención médica</option>
                <option value="Busqueda activa" {{(old('case_type') == 'Busqueda activa') ? 'selected' : '' }}>Busqueda activa</option>
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

    </div>
<!-------------------------------->
    <hr>


    <div class="form-row">

        <fieldset class="form-group col-12 col-md-4">
            <label for="for_observation">Observación</label>
            <input type="text" class="form-control" name="observation" value="{{old('observation')}}"
                id="for_observation">
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_paho_flu">PAHO FLU</label>
            <input type="number" class="form-control" name="paho_flu" value="{{old('paho_flu')}}"
                id="for_paho_flu">
        </fieldset>

        {{-- <fieldset class="form-group col-6 col-md-2">
            <label for="for_run_medic">Run Médico Solicitante *</label>
            <input type="text" class="form-control" name="run_medic" id="for_run_medic"
                required placeholder="Ej: 12345678-9">
        </fieldset> --}}

        <fieldset class="form-group col-1 col-md-1">
            <label for="for_run_medic_s_dv">Orden Méd. </label>
            <select name="medical_order" id="for_medical_order" class="form-control" required>
                <option value="1" {{(old('medical_order') == '1') ? 'selected' : '' }}>Sí</option>
                <option value="0" {{(old('medical_order') == '0') ? 'selected' : '' }}>No</option>
            </select>

        </fieldset>

        <fieldset class="form-group col-8 col-md-2">
            <label for="for_run_medic_s_dv">Run Médico SIN DV </label>
            <input type="number" class="form-control" id="for_run_medic_s_dv" name="run_medic_s_dv" value="{{old('run_medic_s_dv')}}">
        </fieldset>

        <fieldset class="form-group col-4 col-md-1">
            <label for="for_run_medic_dv">DV </label>
            <input type="text" class="form-control" id="for_run_medic_dv" name="run_medic_dv" value="{{old('run_medic_dv')}}" readonly>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_epivigila" title="Si es BAC: rutUsuario-folioBAC, &#013;si no, solo nro. epivigila">Epivigila *</label>
            <input type="text" class="form-control" id="for_epivigila" value="{{old('epivigila')}}"
                name="epivigila" maxlength="255" required title="Si es BAC: rutUsuario-folioBAC, &#013;si no, solo nro. epivigila" placeholder="rutUsuario-folioBAC">
        </fieldset>

    </div>

    <hr>
    <h4 class="mt-4">Examen Rápido (Opcional)</h4>
    <div class="form-row">
    <fieldset class="form-group col-5 col-md-3">
            <label for="for_register_at">Fecha de Muestra de Antígeno</label>
            <input type="datetime-local" class="form-control" id="for_register_at"
                name="register_at" value="{{ date('Y-m-d\TH:i:s') }}"  max="{{ date('Y-m-d\TH:i:s') }}">
    </fieldset>



    <fieldset class="form-group col-3 col-md-3">
    <label for="for_value_test">Valor de Antígeno</label>
    <select name="value_test" id="for_value_test" class="form-control">
                <option value="" {{(old('value_test') == '') ? 'selected' : '' }}></option>
                <option value="Positive" {{(old('value_test') == 'Positive') ? 'selected' : '' }}>Positivo</option>
                <option value="Negative" {{(old('value_test') == 'Negative') ? 'selected' : '' }}>Negativo</option>
                <option value="Weak" {{(old('value_test') == 'Weak') ? 'selected' : '' }}>No Válido</option>
            </select>
    </fieldset>



    </div>

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
            if(data.gender === 'male'){
                $('#for_gestation').val('0');
                $('#for_gestation option:not(:selected)').attr('disabled', 'disabled');
            }
            else{
                $('#for_gestation').val('');
                $('#for_gestation option').removeAttr('disabled', 'disabled');
            }
            document.getElementById("for_status").value = data.status;
        } else {
            document.getElementById("for_id").value = "";
            document.getElementById("for_other_identification").value = "";
            document.getElementById("for_gender").value = "";
            document.getElementById("for_birthday").value = "";
            document.getElementById("for_name").value = "";
            document.getElementById("for_fathers_family").value = "";
            document.getElementById("for_mothers_family").value = "";
            document.getElementById("for_gestation").value = "";
            $('#for_gestation').val('');
            $('#for_gestation option').removeAttr('disabled', 'disabled');

        }
    });
});

$('input[name=other_identification]').change(function() {
    var str = $("#for_other_identification").val();
    $.get('{{ route('patients.getotheridentification')}}/'+str, function(data) {
        if(data){
            document.getElementById("for_id").value = data.id;
            // document.getElementById("for_other_identification").value = data.other_identification;
            document.getElementById("for_gender").value = data.gender;
            document.getElementById("for_birthday").value = data.birthday;
            document.getElementById("for_name").value = data.name;
            document.getElementById("for_fathers_family").value = data.fathers_family;
            document.getElementById("for_mothers_family").value = data.mothers_family;
            if(data.gender === 'male'){
                $('#for_gestation').val('0');
                $('#for_gestation option:not(:selected)').attr('disabled', 'disabled');
            }
            else{
                $('#for_gestation').val('');
                $('#for_gestation option').removeAttr('disabled', 'disabled');
            }
            document.getElementById("for_status").value = data.status;
        } else {
            document.getElementById("for_id").value = "";
            // document.getElementById("for_other_identification").value = "";
            document.getElementById("for_gender").value = "";
            document.getElementById("for_birthday").value = "";
            document.getElementById("for_name").value = "";
            document.getElementById("for_fathers_family").value = "";
            document.getElementById("for_mothers_family").value = "";
            document.getElementById("for_gestation").value = "";
            $('#for_gestation').val('');
            $('#for_gestation option').removeAttr('disabled', 'disabled');
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

            //CALCULO DE FECHA EN CACHO QUE EXISTA EL DATO DE FECHA DE NACIMIENTO
            var birthDate =data.birthday;
            var d = new Date(birthDate);
            var mdate = birthDate.toString();
            var yearThen = parseInt(mdate.substring(0,4), 10);
            var monthThen = parseInt(mdate.substring(5,7), 10);
            var dayThen = parseInt(mdate.substring(8,10), 10);
            var today = new Date();
            var birthday = new Date(yearThen, monthThen-1, dayThen);
            var differenceInMilisecond = today.valueOf() - birthday.valueOf();
            var year_age = Math.floor(differenceInMilisecond / 31536000000);
            $("#for_age").val(year_age);
            //FIN DE CALCULO DE EDAD


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

});
</script>



<script type="text/javascript">
$(document).ready(function(){
    $("#for_gender").change(function(){
        var selectedcategory = $(this).children("option:selected").val();
        if(selectedcategory =='male')
        {
            $('#for_gestation').val('0');
            $('#for_gestation option:not(:selected)').attr('disabled', 'disabled');
        }
        else
        {
            $('#for_gestation').val('');
            $('#for_gestation option').removeAttr('disabled', 'disabled');
        }
    });

    //Run y otra identificación excluyentes
    $("#for_other_identification").click(function () {
        $("#for_run").val("");
        $("#for_dv").val("");
        $("#for_run").attr('readonly', 'readonly');
        $("#for_other_identification").removeAttr('readonly', 'readonly');
    })

    $("#for_run").click(function () {
        $("#for_other_identification").val("");
        $("#for_other_identification").attr('readonly', 'readonly');
        $("#for_run").removeAttr('readonly', 'readonly');
    })

    $("#for_birthday").change(function () {
        var birthDate =document.getElementById('for_birthday').value;
        var d = new Date(birthDate);

        var mdate = birthDate.toString();
        var yearThen = parseInt(mdate.substring(0,4), 10);
        var monthThen = parseInt(mdate.substring(5,7), 10);
        var dayThen = parseInt(mdate.substring(8,10), 10);

        var today = new Date();
        var birthday = new Date(yearThen, monthThen-1, dayThen);

        var differenceInMilisecond = today.valueOf() - birthday.valueOf();

        var year_age = Math.floor(differenceInMilisecond / 31536000000);
        $("#for_age").val(year_age);

    })

});




</script>
@endsection
