@extends('layouts.app')

@section('title', 'Editar paciente pendiente')

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


    <form method="POST" class="form-horizontal" action="{{ route('pending_patient.update', $pendingPatient) }}">
        @csrf
        @method('PUT')
        <div class="card mb-3">
            <div class="card-body">
                <div class="form-row">
                    <fieldset class="form-group col-12 col-sm-7 col-md-4">
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

                </div>
                <div class="form-row">

                    <fieldset class="form-group col-12 col-md-4 geo">
                        <label for="for_address">Dirección *</label>
                        <input type="text" class="form-control" name="address" id="for_address"
                               value="{{$pendingPatient->address}}">
                    </fieldset>

                    <fieldset class="form-group col-12 col-md-4">
                        <label for="regiones">Región *</label>
                        <select class="form-control" name="region_id" id="regiones">
                            <option>Seleccione Región</option>
                            @foreach ($regions as $key => $region)
                                <option
                                    value="{{$region->id}}" {{ ($region->id == $pendingPatient->region_id) ? 'selected' : '' }} >{{$region->name}}</option>
                            @endforeach
                        </select>
                    </fieldset>

                    <fieldset class="form-group col-12 col-md-4">
                        <label for="comunas">Comuna *</label>
                        <select class="form-control geo" name="commune_id" id="comunas"
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
                            <option value="not_contacted">No contactado</option>
                            <option value="updated_information" selected>Información actualizada</option>
                            <option value="contacted">Contactado</option>
                        </select>
                    </fieldset>
                </div>


                <!--------------------------->
                <button type="submit" class="btn btn-primary">Guardar</button>

                <a class="btn btn-outline-secondary" href="{{ route('pending_patient.index') }}">
                    Cancelar
                </a>
            </div>
        </div>
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

        });

    </script>
@endsection
