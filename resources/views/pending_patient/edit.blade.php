@extends('layouts.app')

@section('title', 'Editar paciente pendiente')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <h3 class="mb-3 d-print-none">Paciente no contactado</h3>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-4 text-right">
            <input type="button" class="d-print-none" value="Imprimir" onclick="javascript:window.print()">
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

    <div class="noScreen">
        <div class="row mb-3">
            <div class="col-md-3 text-center">
                <img src="{{ asset('/images/lab_1.png') }}" class="img-fluid mb-2" alt="Servicio de Salud Iquique" width="40%"><br>
                <span  >SERVICIO DE SALUD IQUIQUE</span>
            </div>
        </div>
    </div>

    <div class="noScreen">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 text-center">
                <H2>CITACION DOMICILIARIA</H2>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>


    <form method="POST" class="form-horizontal" action="{{ route('pending_patient.update', $pendingPatient) }}">
        @csrf
        @method('PUT')
        <div class="card mb-3">
            <h5 class="card-header">Paciente</h5>
            <div class="card-body">
                <div class="form-row">

                    <fieldset class="form-group col-10 col-sm-4 col-md-3 col-lg-3">
                        <label for="for_run">Run</label>
                        <input type="text" class="form-control" id="for_run" name="run" autocomplete="off" max="80000000" value="{{$pendingPatient->run}}">
                    </fieldset>

                    <fieldset class="form-group col-2 col-sm-2 col-md-1 col-lg-1">
                        <label for="for_dv">DV</label>
                        <input type="text" class="form-control" id="for_dv" name="dv" autocomplete="off" readonly value="{{$pendingPatient->dv}}">
                    </fieldset>

                    <fieldset class="form-group col-12 col-sm-6 col-md-4">
                        <label for="for_other_identification">Run Provisorio</label>
                        <input type="text" class="form-control" id="for_other_identification" name="other_identification" autocomplete="off" value="{{$pendingPatient->other_identification}}">
                    </fieldset>

                    <fieldset class="form-group col-12 col-sm-6 col-md-4">
                        <label for="for_name">Nombres*</label>
                        <input type="text" class="form-control" id="for_name" name="name"
                               style="text-transform: uppercase;"
                               required autocomplete="off" value="{{$pendingPatient->name}}">
                    </fieldset>

                    <fieldset class="form-group col-12 col-sm-6 col-md-4">
                        <label for="for_fathers_family">Apellido Paterno*</label>
                        <input type="text" class="form-control" id="for_fathers_family"
                               name="fathers_family" style="text-transform: uppercase;"
                               required autocomplete="off" value="{{$pendingPatient->fathers_family}}">
                    </fieldset>

                    <fieldset class="form-group col-12 col-sm-6 col-md-4">
                        <label for="for_mothers_family">Apellido Materno</label>
                        <input type="text" class="form-control" id="for_mothers_family"
                               name="mothers_family" style="text-transform: uppercase;" autocomplete="off" value="{{$pendingPatient->mothers_family}}">
                    </fieldset>

                    <fieldset class="form-group col-12 col-sm-6 col-md-4">
                        <label for="for_file_number">Nº Ficha*</label>
                        <input type="text" class="form-control" id="for_file_number"
                               name="file_number" autocomplete="off" required value="{{$pendingPatient->file_number}}">
                    </fieldset>

                </div>
                <div class="form-row">

                    <fieldset class="form-group col-12 col-md-4 geo">
                        <label for="for_address">Dirección *</label>
                        <input type="text" class="form-control" name="address" id="for_address"
                               value="{{$pendingPatient->address}}">
                    </fieldset>

                    <fieldset class="form-group col-12 col-md-4">
                        <label for="regiones">Región *</label>
                        <select class="form-control" name="region_id" id="regiones" required>
                            <option>Seleccione Región</option>
                            @foreach ($regions as $key => $region)
                                <option
                                    value="{{$region->id}}" {{ ($region->id == $pendingPatient->region_id) ? 'selected' : '' }} >{{$region->name}}</option>
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
                               style="text-transform: lowercase;" value="{{$pendingPatient->email}}">
                    </fieldset>

                    <fieldset class="form-group col-12 col-md-4">
                        <label for="for_telephone">Teléfono *</label>
                        <input type="text" class="form-control" name="telephone" id="for_telephone"
                               value="{{$pendingPatient->telephone}}">
                    </fieldset>

                    <fieldset class="form-group col-12 col-md-4">
                        <label for="for_status">Estado</label>
                        <select name="status" id="for_status" class="form-control">
                            <option value="not_contacted" {{($pendingPatient->status == 'not_contacted') ? 'selected' : '' }}>No contactado</option>
                            <option value="updated_information" {{($pendingPatient->status == 'updated_information') ? 'selected' : '' }}>Información actualizada</option>
                            <option value="contacted" {{($pendingPatient->status == 'contacted') ? 'selected' : '' }} >Contactado</option>
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
                            <option value="ges" {{($pendingPatient->reason == 'ges') ? 'selected' : '' }} >GES</option>
                            <option value="le" {{($pendingPatient->reason == 'le') ? 'selected' : '' }}>Lista de espera</option>
                            <option value="control" {{($pendingPatient->reason == 'control') ? 'selected' : '' }}>Control</option>
                            <option value="procedure" {{($pendingPatient->reason == 'procedure') ? 'selected' : '' }}>Procedimiento</option>
                        </select>
                    </fieldset>
                    <fieldset class="form-group col-12 col-md-4">
                        <label for="for_appointment_with">Citación con</label>
                        <input type="text" class="form-control" name="appointment_with" id="for_appointment_with" value="{{$pendingPatient->appointment_with}}">
                    </fieldset>

                    <fieldset class="form-group col-12 col-md-4">
                        <label for="for_appointment_specialty">Especialidad</label>
                        <select class="form-control" name="appointment_specialty" id="for_appointment_specialty">
                            <option value="">Seleccionar</option>
                            @foreach($specialties as $specialty)
                                <option value="{{$specialty->id}}" {{($specialty->id == $pendingPatient->appointment_specialty) ? 'selected' : '' }}>{{$specialty->name}}</option>
                            @endforeach


                        </select>
                    </fieldset>

                    <fieldset class="form-group col-12 col-md-4">
                        <label for="for_appointment_at">Fecha citación</label>
                        <input type="datetime-local" class="form-control" name="appointment_at" id="for_appointment_at" value="{{($pendingPatient->appointment_at) ? $pendingPatient->appointment_at->format('Y-m-d\TH:i:s') : null }}" >
                    </fieldset>
                    <fieldset class="form-group col-12 col-md-4">
                        <label for="for_appointment_location">Lugar a presentarse</label>
                        <input type="text" class="form-control" name="appointment_location" id="for_appointment_location" value="{{$pendingPatient->appointment_location}}">
                    </fieldset>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <h5 class="card-header">Responsable recepción citación</h5>
            <div class="card-body">
                <div class="form-row">
                    <fieldset class="form-group col-12 col-md-4">
                        <label for="for_responsible_name">Nombre</label>
                        <input type="text" class="form-control" name="responsible_name" id="for_responsible_name" value="{{$pendingPatient->responsible_name}}">
                    </fieldset>

                    <fieldset class="form-group col-12 col-md-4">
                        <label for="for_responsible_run">Run</label>
                        <input type="text" class="form-control" id="for_responsible_run" name="responsible_run" value="{{$pendingPatient->responsible_run}}">
                    </fieldset>

                    <fieldset class="form-group col-12 col-md-4">
                        <label for="for_responsible_phone">Fono</label>
                        <input type="text" class="form-control" id="for_responsible_phone" name="responsible_phone" value="{{$pendingPatient->responsible_phone}}">
                    </fieldset>

                </div>
            </div>
        </div>

        <div class="card mb-3">
            <h5 class="card-header">Visita</h5>
            <div class="card-body">
                <div class="form-row">
                    <fieldset class="form-group col-12 col-md-12">
                        <label for="for_visit_observation">Observación de visita domiciliaria</label>
                        <input type="text" class="form-control" id="for_visit_observation" name="visit_observation" value="{{$pendingPatient->visit_observation}}">
                    </fieldset>

                    <fieldset class="form-group col-12 col-md-4">
                        <label for="for_visit_delivery_at">Fecha de entrega</label>
                        <input type="datetime-local" class="form-control" id="for_visit_delivery_at" name="visit_delivery_at" value="{{ ($pendingPatient->visit_delivery_at) ? $pendingPatient->visit_delivery_at->format('Y-m-d\TH:i:s') : null}}">
                    </fieldset>

                    <fieldset class="form-group col-12 col-md-8">
                        <label for="for_visit_appointment_functionary">Funcionario que entrega citación</label>
                        <input type="text" class="form-control" id="for_visit_appointment_functionary" name="visit_appointment_functionary" value="{{$pendingPatient->visit_appointment_functionary}}">
                    </fieldset>

                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary d-print-none">Guardar</button>

        <a class="btn btn-outline-secondary d-print-none" href="{{ route('pending_patient.index') }}">
            Cancelar
        </a>

        <a class="btn btn-danger d-print-none float-right" href="{{route('pending_patient.destroy', $pendingPatient)}}"
           onclick="return confirm('¿Está seguro que desea eliminar al paciente : {{$pendingPatient->name . ' ' . $pendingPatient->fathers_family}}? ' )">
            Eliminar
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


            //si es que existe, se carga la region y comuna
            var htmlComuna = '<option value="sin-comuna">Seleccione comuna</option><option value="sin-comuna">--</option>';
            var valorRegion = '{{$pendingPatient->region_id}}';
            @foreach ($communes as $key => $commune)
            if (valorRegion == '{{$commune->region_id}}') {
                if ('{{$pendingPatient->commune_id}}' == '{{$commune->id}}') {
                    htmlComuna = htmlComuna + '<option selected value="' + '{{$commune->id}}' + '">' + '{{$commune->name}}' + '</option>';
                } else {
                    htmlComuna = htmlComuna + '<option value="' + '{{$commune->id}}' + '">' + '{{$commune->name}}' + '</option>';
                }
            }
            @endforeach
            jQuery('#comunas').html(htmlComuna);

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

<style type="text/css">
    @media screen
    {
        .noPrint{}
        .noScreen{display:none;}
    }

    @media print
    {
        .noPrint{display:none;}
        .noScreen{}
    }
</style>
