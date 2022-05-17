@extends('layouts.app')

@section('title', 'Crear Paciente')

@section('content')
<h3 class="mb-3">Crear Paciente</h3>
@if ($errors->any())
    <div class="alert alert-warning">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<form method="POST" class="form-horizontal" action="{{ route('patients.store') }}">
    @csrf
    @method('POST')
<!--------------------------->
    <div class="form-row align-items-end">
        <fieldset class="form-group col-5 col-sm-4 col-md-4 col-lg-2">
            <label for="for_run">Run</label>
            <input type="number" class="form-control" id="for_run" name="run" autocomplete="off" max="80000000" required>
        </fieldset>

        <fieldset class="form-group col-2 col-sm-2 col-md-1 col-lg-1">
            <label for="for_dv">DV</label>
            <input type="text" class="form-control" id="for_dv" name="dv" autocomplete="off" readonly>
        </fieldset>

        <fieldset class="form-group col-5 col-sm-6 col-md-2 col-lg-1">
            <label for="">&nbsp;</label>
            <button type="button" id="btn_fonasa" class="btn btn-outline-success">Fonasa&nbsp;</button>
        </fieldset>

        <fieldset class="form-group col-6 col-sm-4 col-md-4 col-lg-3">
            <label for="for_other_identification">Otra identificación</label>
            <input type="text" class="form-control" id="for_other_identification"
                placeholder="Extranjeros sin run" name="other_identification" autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-6 col-sm-4 col-md-3 col-lg-2">
            <label for="for_gender">Género</label>
            <select name="gender" id="for_gender" class="form-control">
                <option value="male">Masculino</option>
                <option value="female">Femenino</option>
                <option value="other">Otro</option>
                <option value="unknown">Desconocido</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-7 col-sm-4 col-md-3 col-lg-2">
            <label for="for_birthday">Fecha Nacimiento</label>
            <input type="date" class="form-control" id="for_birthday" min="1900-01-01" max="{{Carbon\Carbon::now()->toDateString()}}"
                name="birthday" required>
        </fieldset>
    </div>
<!--------------------------->
    <div class="form-row">
        <fieldset class="form-group col-12 col-sm-7 col-md-4">
            <label for="for_name">Nombres*</label>
            <input type="text" class="form-control" id="for_name" name="name"
                style="text-transform: uppercase;"
                required autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-12 col-sm-6 col-md-4">
            <label for="for_fathers_family">Apellido Paterno*</label>
            <input type="text" class="form-control" id="for_fathers_family"
                name="fathers_family" style="text-transform: uppercase;"
                required autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-12 col-sm-6 col-md-4">
            <label for="for_mothers_family">Apellido Materno</label>
            <input type="text" class="form-control" id="for_mothers_family"
                name="mothers_family" style="text-transform: uppercase;" autocomplete="off">
        </fieldset>

    </div>
<!--------------------------->
    <div class="card mb-3">
        <div class="card-body">
            @include('patients.demographic.create')
        </div>
    </div>
<!--------------------------->
    <button type="submit" class="btn btn-primary">Guardar</button>

    <a class="btn btn-outline-secondary" href="{{ route('patients.index') }}">
        Cancelar
    </a>


</form>


@endsection

@section('custom_js')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-select.min.css') }}">

    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('js/defaults-es_CL.min.js') }}"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://js.api.here.com/v3/3.1/mapsjs-core.js" type="text/javascript" charset="utf-8"></script>
    <script src="https://js.api.here.com/v3/3.1/mapsjs-service.js" type="text/javascript" charset="utf-8"></script>

    <script src='{{asset("js/jquery.rut.chileno.js")}}'></script>

    <script type="text/javascript">

        jQuery(document).ready(function () {

            var iRegion = 0;

            // caso cuando se cambie manualmente
            jQuery('#regiones').change(function () {
                // var iRegiones = 0;
                var valorRegion = jQuery(this).val();
                var htmlComuna = '<option value="sin-comuna">Seleccione comuna</option><option value="sin-comuna">--</option>';
                @foreach ($communes as $key => $commune)
                if (valorRegion == '{{$commune->region_id}}') {
                    htmlComuna = htmlComuna + '<option value="' + '{{$commune->id}}' + '">' + '{{$commune->name}}' + '</option>';
                }
                @endforeach
                jQuery('#comunas').html(htmlComuna);
            });

            //obtener coordenadas
            jQuery('.geo').change(function () {
                // Instantiate a map and platform object:
                var platform = new H.service.Platform({
                    'apikey': '{{ env('API_KEY_HERE') }}'
                });

                var address = jQuery('#for_address').val();
                var number = jQuery('#for_number').val();
                // var regiones = jQuery('#regiones').val();
                // var comunas = jQuery('#comunas').val();
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


            //obtiene digito verificador
            $('input[name=run]').keyup(function(e) {
                var str = $("#for_run").val();
                $('#for_dv').val($.rut.dv(str));
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

            //Run y otra identificación excluyentes
            $("#for_other_identification").click(function () {
                $("#for_run").val("");
                $("#for_dv").val("");
                $("#for_run").attr('readonly', 'readonly');
                $("#for_other_identification").removeAttr('readonly', 'readonly');
                $("#for_run").removeAttr('required')
                $("#for_other_identification").attr('required', 'required');
            })

            $("#for_run").click(function () {
                $("#for_other_identification").val("");
                $("#for_other_identification").attr('readonly', 'readonly');
                $("#for_run").removeAttr('readonly', 'readonly');
                $("#for_other_identification").removeAttr('required')
                $("#for_run").attr('required', 'required');
            })

        });

    </script>
@endsection
