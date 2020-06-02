@extends('layouts.app')

@section('title', 'Agregar Datos Receptor Canasta Familiar')
@section('content')
@include('help_basket.nav')
<h3 class="mb-3">Agregar Datos Receptor Canasta Familiar</h3>

<form method="POST" class="form-horizontal" action="{{ route('help_basket.store')  }}" enctype="multipart/form-data">
    @csrf
    @method('POST')

    <div class="form-row">
        <fieldset class="form-group col-md-2">
            <label for="for_run">Run (sin digito)</label>
            <input type="number" class="form-control" name="run" autocomplete="off" id="for_run" style="text-transform: uppercase;">
        </fieldset>

        <fieldset class="form-group col-2 col-md-1">
            <label for="for_dv">Digito</label>
            <input type="text" class="form-control" name="dv" id="for_dv" style="text-transform: uppercase;">
        </fieldset>

        <fieldset class="form-group col- col-md-1">
            <label for="">&nbsp;</label>
            <!-- <button type="button" id="btn_fonasa" class="btn btn-outline-success">Buscar Datos&nbsp;</button> -->
        </fieldset>

        <fieldset class="form-group col-1 col-md-1">
            <label for="">&nbsp;</label>
            <span class="form-control-plaintext"> </span>
        </fieldset>

        <fieldset class="form-group col-md-3">
            <label for="for_other_identification">Otra identificación</label>
            <input type="text" class="form-control" name="other_identification" id="for_other_identification" placeholder="Digitar en caso de extranjeros">
        </fieldset>

    </div>
    <div class="form-row">

        <fieldset class="form-group col-md-3">
            <label for="for_name">Nombre *</label>
            <input type="text" class="form-control" name="name" id="for_name" style="text-transform: uppercase;" required autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-md-2">
            <label for="for_fathers_family">Apellido Paterno *</label>
            <input type="text" class="form-control" name="fathers_family" id="for_fathers_family" style="text-transform: uppercase;" required autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-md-2">
            <label for="for_mothers_family">Apellido Materno</label>
            <input type="text" class="form-control" name="mothers_family" id="for_mothers_family" style="text-transform: uppercase;" autocomplete="off">
        </fieldset>
    </div>

    <hr>
    <div class="form-row">
        <fieldset class="form-group col-12 col-md-2">
            <label for="for_street_type">Vía de residencia</label>
            <select name="street_type" id="for_street_type" class="form-control">
                <option value="Calle">Calle</option>
                <option value="Pasaje">Pasaje</option>
                <option value="Avenida">Avenida</option>
                <option value="Camino">Camino</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-12 col-md-4">
            <label for="for_address">Dirección *</label>
            <input type="text" class="form-control geo" name="address" id="for_address" style="text-transform: uppercase;" required autocomplete="off">
        </fieldset>


        <fieldset class="form-group col-6 col-md-2">
            <label for="for_number">Número</label>
            <input type="text" class="form-control geo" name="number" id="for_number" style="text-transform: uppercase;" autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-6 col-md-1">
            <label for="for_department">Depto.</label>
            <input type="text" class="form-control" name="department" id="for_department" style="text-transform: uppercase;">
        </fieldset>
    </div>


    <div class="form-row">

        <fieldset class="form-group col-12 col-md-3">
            <label for="comunas">Comuna *</label>
            <select class="form-control geo" name="commune_id" id="comunas" required>
                <option value="">Seleccione la comuna</option>
                @foreach ($communes as $key => $commune)
                <option value="{{$commune->id}}">{{$commune->name}}</option>
                @endforeach
            </select>
        </fieldset>
    </div>

    <div class="form-row">
        <fieldset class="form-group col-6 col-md-2">
            <label for="for_latitude">Latitud</label>
            <input type="number" step="00.00000001" class="form-control" name="latitude" id="for_latitude" readonly>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_longitude">Longitud</label>
            <input type="number" step="00.00000001" class="form-control" name="longitude" id="for_longitude" readonly>
        </fieldset>
    </div>

    <div class="form-row">
        <fieldset class="form-group col-12 col-md-3">
            <label for="for_photoid">Foto Cédula de Identidad</label>
            <div class="custom-file">
                <input type="file" name="photoid" class="custom-file-input" id="customFileLang" lang="es">
                <label class="custom-file-label" for="customFileLang">Seleccionar Foto Cédula</label>
            </div>
        </fieldset>
    </div>


    <div class="form-row">
        <fieldset class="form-group col-12 col-md-3">
            <label for="for_photo">Foto Frontal</label>
            <div class="custom-file">
                <input type="file" name="photo" class="custom-file-input" id="customFileLang" lang="es">
                <label class="custom-file-label" for="customFileLang">Seleccionar Foto Frontal</label>
            </div>
        </fieldset>
    </div>

    <div class="form-row">
        <fieldset class="form-group col-12 col-md-5">
            <label for="for_observations">Observaciones</label>
            <textarea type="textarea" class="form-control" rows="4" name="observations" id="for_observations"> </textarea>
        </fieldset>
    </div>



    <hr>


    <button type="submit" class="btn btn-primary">Guardar</button>
    <a class="btn btn-outline-secondary" href="{{ route('help_basket.index')  }}">Cancelar</a>

</form>

@endsection

@section('custom_js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-core.js" type="text/javascript" charset="utf-8"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-service.js" type="text/javascript" charset="utf-8"></script>
<script src='{{asset("js/jquery.rut.chileno.js")}}'></script>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        //obtiene digito verificador
        $('input[name=run]').keyup(function(e) {
            var str = $("#for_run").val();
            $('#for_dv').val($.rut.dv(str));
        });






        //GEO
        //obtener coordenadas
        jQuery('.geo').change(function() {
            // Instantiate a map and platform object:
            var platform = new H.service.Platform({
                'apikey': '5mKawERqnzL1KMnNIt4n42gAV8eLomjQPKf5S5AAcZg'
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
                };
                console.log(geocodingParams);

                // Define a callback function to process the geocoding response:

                jQuery('#for_latitude').val("");
                jQuery('#for_longitude').val("");
                var onResult = function(result) {
                    console.log(result);
                    var locations = result.Response.View[0].Result;

                    // Add a marker for each location found
                    for (i = 0; i < locations.length; i++) {
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

@endsection