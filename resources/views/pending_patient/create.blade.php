@extends('layouts.app')

@section('title', 'Paciente no contactado')

@section('content')
    <h3 class="mb-3">Paciente no contactado</h3>
    @if ($errors->any())
        <div class="alert alert-warning">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form method="POST" class="form-horizontal" action="{{ route('pending_patient.store') }}">
    @csrf
    @method('POST')
        <div class="card mb-3">
            <h5 class="card-header">Paciente</h5>
            <div class="card-body">
                <div class="form-row">
                    <fieldset class="form-group col-10 col-sm-4 col-md-3 col-lg-3">
                        <label for="for_run">Run</label>
                        <input type="text" class="form-control" id="for_run" name="run" autocomplete="off" max="80000000">
                    </fieldset>

                    <fieldset class="form-group col-2 col-sm-2 col-md-1 col-lg-1">
                        <label for="for_dv">DV</label>
                        <input type="text" class="form-control" id="for_dv" name="dv" autocomplete="off" readonly>
                    </fieldset>

                    <fieldset class="form-group col-12 col-sm-6 col-md-4">
                        <label for="for_other_identification">Run Provisorio</label>
                        <input type="text" class="form-control" id="for_other_identification" name="other_identification" autocomplete="off">
                    </fieldset>

                    <fieldset class="form-group col-12 col-sm-6 col-md-4">
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

                    <fieldset class="form-group col-12 col-sm-6 col-md-4">
                        <label for="for_file_number">Nº Ficha*</label>
                        <input type="text" class="form-control" id="for_file_number"
                               name="file_number" autocomplete="off" required>
                    </fieldset>

                </div>
                <div class="form-row">

                    <fieldset class="form-group col-12 col-md-4 geo">
                        <label for="for_address">Dirección *</label>
                        <input type="text" class="form-control" name="address" id="for_address" required
                               value="{{old('address')}}">
                    </fieldset>

                    <fieldset class="form-group col-12 col-md-4">
                        <label for="regiones">Región *</label>
                        <select class="form-control" name="region_id" id="regiones" required>
                            <option>Seleccione Región</option>
                            @foreach ($regions as $key => $region)
                                <option
                                    value="{{$region->id}}" {{(old('region_id') == $region->id) ? 'selected' : '' }} >{{$region->name}}</option>
                            @endforeach
                        </select>
                    </fieldset>

                    <fieldset class="form-group col-12 col-md-4">
                        <label for="comunas">Comuna *</label>
                        <select class="form-control geo" name="commune_id" id="comunas" required
                                value="{{old('commune_id')}}" ></select>
                    </fieldset>
                </div>

                <div class="form-row">
                    <fieldset class="form-group col-12 col-md-4">
                        <label for="for_email">email</label>
                        <input type="email" class="form-control" name="email" id="for_email"
                               value="{{old('email')}}"
                               style="text-transform: lowercase;">
                    </fieldset>

                    <fieldset class="form-group col-12 col-md-4">
                        <label for="for_telephone">Teléfono *</label>
                        <input type="text" class="form-control" name="telephone" id="for_telephone"
                               value="{{old('telephone')}}" >
                    </fieldset>

                    <fieldset class="form-group col-12 col-md-4">
                        <label for="for_status">Estado</label>
                        <select name="status" id="for_status" class="form-control">
                            <option value="not_contacted">No contactado</option>
                            <option value="updated_information">Información actualizada</option>
                            <option value="contacted">Contactado</option>
                        </select>
                    </fieldset>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <h5 class="card-header">Citación</h5>
            <div class="card-body">
                <div class="form-row">

                    <fieldset class="form-group col-12 col-md-4">
                        <label for="for_reason">Motivo</label>
                        <select name="reason" id="for_reason" class="form-control">
                            <option value="ges">GES</option>
                            <option value="le">Lista de espera</option>
                            <option value="control">Control</option>
                            <option value="procedure">Procedimiento</option>
                        </select>
                    </fieldset>
                    <fieldset class="form-group col-12 col-md-4">
                        <label for="for_appointment_with">Citación con</label>
                        <input type="text" class="form-control" name="appointment_with" id="for_appointment_with">
                    </fieldset>

                    <fieldset class="form-group col-12 col-md-4">
                        <label for="for_appointment_specialty">Especialidad</label>
                        <select class="form-control" name="appointment_specialty" id="for_appointment_specialty">
                            <option value="">Seleccionar</option>
                            @foreach($specialties as $specialty)
                                <option value="{{$specialty->id}}"> {{$specialty->name}} </option>
                            @endforeach
                        </select>
                    </fieldset>

                    <fieldset class="form-group col-12 col-md-4">
                        <label for="for_appointment_at">Fecha citación</label>
                        <input type="datetime-local" class="form-control" name="appointment_at" id="for_appointment_at">
                    </fieldset>

                    <fieldset class="form-group col-12 col-md-4">
                        <label for="for_appointment_location">Lugar a presentarse</label>
                        <input type="text" class="form-control" name="appointment_location" id="for_appointment_location">
                    </fieldset>

                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a class="btn btn-outline-secondary" href="{{ route('pending_patient.index') }}">
            Cancelar
        </a>

    </form>


@endsection

@section('custom_js')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-select.min.css') }}">

    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('js/defaults-es_CL.min.js') }}"></script>

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

            //obtiene digito verificador
            $('input[name=run]').keyup(function(e) {
                var str = $("#for_run").val();
                $('#for_dv').val($.rut.dv(str));
            });
        });

    </script>
@endsection
