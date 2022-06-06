@extends('layouts.app')

@section('title', 'Editar Paciente')

@section('content')

<div class="card mb-3">
    <div class="card-body">

        <h3 class="mb-3">Ver/Editar Paciente</h3>

        <form method="POST" class="form-horizontal" action="{{ route('patients.update',$patient) }}">
            @csrf
            @method('PUT')
            <!--**********************************-->
            <div class="form-row">
                <fieldset class="form-group col-10 col-sm-4 col-md-2 col-lg-2">
                    <label for="for_run">Run</label>
                    <input type="text" class="form-control" id="for_run" name="run" value="{{ $patient->run }}" max="80000000">
                </fieldset>

                <fieldset class="form-group col-2 col-sm-2 col-md-1 col-lg-1">
                    <label for="for_dv">DV</label>
                    <input type="text" class="form-control" id="for_dv" name="dv" value="{{ $patient->dv }}">
                </fieldset>

                <fieldset class="form-group col-12 col-sm-6 col-md-3 col-lg-3">
                    <label for="for_other_identification">Otra identificación</label>
                    <input type="text" class="form-control" id="for_other_identification" placeholder="Extranjeros sin run" name="other_identification" value="{{ $patient->other_identification }}">
                </fieldset>

                <fieldset class="form-group col-12 col-sm-4 col-md-3 col-lg-3">
                    <label for="for_gender">Género</label>
                    <select name="gender" id="for_gender" class="form-control">
                        <option value="male" {{($patient->gender == 'male')?'selected':''}}>
                            Masculino
                        </option>
                        <option value="female" {{($patient->gender == 'female')?'selected':''}}>
                            Femenino
                        </option>
                        <option value="other" {{($patient->gender == 'other')?'selected':''}}>
                            Otro
                        </option>
                        <option value="unknown" {{($patient->gender == 'unknown')?'selected':''}}>
                            Desconocido
                        </option>
                    </select>
                </fieldset>

                <fieldset class="form-group col-8 col-sm-5 col-md-3 col-lg-2">
                    <label for="for_birthday">Fecha Nacimiento</label>
                    <input type="date" class="form-control" id="for_birthday" required name="birthday" value="{{ ($patient->birthday)?$patient->birthday->format('Y-m-d'):'' }}">
                </fieldset>
            </div>
            <!--**********************************-->
            <div class="form-row">
                <fieldset class="form-group col-12 col-sm-4 col-md-4">
                    <label for="for_name">Nombres*</label>
                    <input type="text" class="form-control" id="for_name" name="name" value="{{ $patient->name }}" style="text-transform: uppercase;" required>
                </fieldset>

                <fieldset class="form-group col-12 col-sm-4 col-md-4">
                    <label for="for_fathers_family">Apellido Paterno*</label>
                    <input type="text" class="form-control" id="for_fathers_family" name="fathers_family" value="{{ $patient->fathers_family }}" style="text-transform: uppercase;" required>
                </fieldset>

                <fieldset class="form-group col-12 col-sm-4 col-md-4">
                    <label for="for_mothers_family">Apellido Materno</label>
                    <input type="text" class="form-control" id="for_mothers_family" name="mothers_family" value="{{ $patient->mothers_family }}" style="text-transform: uppercase;">
                </fieldset>

            </div>
            <!--**********************************-->

            @if($patient->demographic)
            @include('patients.demographic.edit')
            @else
            @include('patients.demographic.create')
            @endif
            <!--**********************************-->
            <div class="form-row">
                <fieldset class="form-group col-12 col-sm-6 col-lg-3">
                    <label for="for_status">Estado</label>
                    <select name="status" id="for_status" class="form-control">
                        <option value=""></option>
                        <option value="Alta" {{ ($patient->status == 'Alta')?'selected':'' }}>Alta</option>
                        <option value="Ambulatorio" {{ ($patient->status == 'Ambulatorio')?'selected':'' }}>
                            Ambulatorio (domiciliario)
                        </option>
                        <option value="Fallecido" {{ ($patient->status == 'Fallecido')?'selected':'' }}>Fallecido
                        </option>
                        <option value="Fugado" {{ ($patient->status == 'Fugado')?'selected':'' }}>Fugado</option>
                        <option value="Hospitalizado Básico" {{ ($patient->status == 'Hospitalizado Básico')?'selected':'' }}>
                            Hospitalizado Básico
                        </option>
                        <option value="Hospitalizado Medio" {{ ($patient->status == 'Hospitalizado Medio')?'selected':'' }}>
                            Hospitalizado Medio
                        </option>
                        <option value="Hospitalizado UTI" {{ ($patient->status == 'Hospitalizado UTI')?'selected':'' }}>
                            Hospitalizado UTI
                        </option>
                        <option value="Hospitalizado UCI" {{ ($patient->status == 'Hospitalizado UCI')?'selected':'' }}>
                            Hospitalizado UCI
                        </option>
                        <option value="Hospitalizado UCI (Ventilador)" {{ ($patient->status == 'Hospitalizado UCI (Ventilador)')?'selected':'' }}>
                            Hospitalizado UCI (Ventilador)
                        </option>
                        <option value="Otra Institución" {{ ($patient->status == 'Otra Institución')?'selected':'' }}>
                            Otras instituciones (SENAME, Penitenciaria, etc.)
                        </option>
                        <option value="Residencia Sanitaria" {{ ($patient->status == 'Residencia Sanitaria')?'selected':'' }}>
                            Residencia Sanitaria
                        </option>


                    </select>
                </fieldset>

                <fieldset class="form-group col-8 col-sm-5 col-md-3 col-lg-2">
                    <label for="for_deceased_at">Fallecido</label>
                    <input type="date" class="form-control" name="deceased_at" id="for_deceased_at" value="{{ ($patient->deceased_at) ? $patient->deceased_at->format('Y-m-d') : '' }}">
                </fieldset>
            </div>
            <!--**********************************-->
            <div class="row align-items-center">
                @can('Patient: edit')
                <div class="col-4 col-sm-2 col-md-2 col-lg-2">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                @endcan

                @can('Patient: show')
                <div class="col-4 col-sm-2 col-md-2 col-lg-2">
                    <button type="button" onclick="history.back()" class="btn btn-secondary">Volver</button>
                </div>
                @endcan
                <!--<div class="col-4 px-1 text-center">
                          <a class="btn btn-outline-secondary" href="{{ route('patients.index') }}">Cancelar</a>
                      </div>-->

                <!--
                      <div class="col-1">
                          <p></p>
                      </div>
                      -->
        </form>

        @can('Patient: delete')
        @if($patient->suspectCases->count() === 0)
        <div class="col-4 col-sm-2 col-md-2 col-lg-4">
            <form method="POST" class="form-horizontal" action="{{ route('patients.destroy',$patient) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro que desea eliminar al paciente : {{$patient->fullName}}? ' )">
                    Borrar
                </button>
            </form>
        </div>
        @else
        <div class="col-8 col-sm-9 offset-sm-1 offset-md-3 col-md-7 offset-lg-4 col-lg-6">
            <button class="btn btn-outline-danger float-right" disabled>No es posible eliminar, tiene exámenes
                asociados
            </button>
        </div>
        @endif
        @endcan

    </div>
    <!--**********************************-->
</div>
</div>

<hr>

@include('patients.tracing.partials.show')

<hr>

<div class="card">
    <div class="card-body">
        <h4 class="mt-3">Contactos</h4>

        @canany(['SanitaryResidence: survey','Developer','Patient: tracing'])
        <a class="btn btn-primary btn-sm" href="{{ route('patients.contacts.create', ['search'=>'search_false', 'id' => $patient->id]) }}">
            <i class="fas fa-plus"></i> Nuevo Contacto
        </a>
        @endcan
        <!-- <table class="table table-sm table-bordered small mb-4 mt-4"> -->
        <div class="table-responsive-md">
            <table class="table table-sm table-bordered small mb-4 mt-4">
                <thead>
                    <tr class="text-center">
                        <th rowspan="2">Nombre Paciente</th>
                        <th colspan="7">Contacto</th>
                        <th rowspan="2">Observación</th>
                        <th rowspan="2">Fecha de carga</th>
                    </tr>
                    <tr class="text-center">
                        <th>Fecha Último Contacto</th>
                        <th>Categoría</th>
                        <th>Es</th>
                        <th>Parentesco</th>
                        <th>RUN</th>
                        <th>Nombre</th>
                        <th>¿Viven Juntos?</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patient->contactPatient as $contact)
                    <tr>
                        <td class="text-right">{{ $patient->fullName }}</td>
                        <td class="text-right">{{ $contact->last_contact_at }}</td>
                        <td class="text-right">{{ $contact->CategoryDesc }}</td>
                        <td class="text-center">
                            @if($contact->index == 1)
                            Tiene como
                            @else
                            Es
                            @endif
                        </td>
                        <td class="text-right">{{ $contact->RelationshipName }}</td>
                        <td class="text-right">{{ $contact->patient->identifier }}</td>
                        <td class="text-right">
                            @can('Patient: edit')
                            <a href="{{ route('patients.edit', $contact->contact_id) }}">
                                @endcan
                                {{ $contact->patient->fullName }}
                                @can('Patient: edit')
                            </a>
                            @endcan
                        </td>
                        <td class="text-right">{{ $contact->LiveTogetherDesc }}</td>
                        <td class="text-right">{{ $contact->comment }}</td>
                        <td class="text-right">{{ $contact->created_at->format('d-m-Y') }}</td>
                        @can('Patient: tracing')
                        <td>
                            <a class="btn btn-danger btn-sm" href="{{ route('patients.contacts.destroy', $contact) }}" onclick="return confirm('¿Está seguro que desea eliminar el contacto estrecho con el paciente {{$contact->patient->fullName}}?' )">
                                <i class="far fa-trash-alt"></i>
                            </a>
                            <!-- <a class="btn btn-light btn-sm" href="{{ route('patients.contacts.edit', $contact) }}">
                  <i class="far fa-edit"></i>
              </a> -->
                        </td>
                        @endcan
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<hr>

@can('Patient: tracing')

<div class="card">
    <div class="card-body">
        <h4 class="mt-4">Exámenes PCR</h4>
        <div class="table-responsive-md">
            <table class="table table-sm table-bordered small mb-4 mt-4">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Establecimiento</th>
                        <th>Fecha muestra</th>
                        <th>Fecha resultado</th>
                        <th>Resultado</th>
                        <th>Epivigila</th>
                        <th>Observación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patient->suspectCases as $case)
                    <tr>
                        <td>
                            @can('SuspectCase: edit')
                            <a href="{{ route('lab.suspect_cases.edit', $case )}}">
                                @endcan
                                {{ $case->id }}
                                @can('SuspectCase: edit')
                            </a>
                            @endcan
                        </td>
                        <td>{{ $case->establishment?$case->establishment->alias.' - '.$case->origin: '' }}</td>
                        <td>{{ $case->sample_at }}</td>
                        <td>{{ $case->pcr_sars_cov_2_at }}</td>
                        <td>{{ $case->covid19 }}
                            @if($case->file)
                            <a href="{{ route('lab.suspect_cases.download', $case->id) }}" target="_blank"><i class="fas fa-paperclip"></i>&nbsp
                            </a>
                            @endif

                            @if ($case->laboratory)
                            @if ($case->laboratory->pdf_generate == 1 && $case->pcr_sars_cov_2 <> 'pending')
                                <a href="{{ route('lab.print', $case) }}" target="_blank"><i class="fas fa-paperclip"></i>&nbsp
                                </a>
                                @endif
                                @endif
                        </td>
                        <td>{{ $case->epivigila }}</td>
                        <td>{{ $case->observation }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endcan

<hr>

@can('Rapid Test: list')

<div class="card">
    <div class="card-body">
        <h4 class="mt-4">Test Rápido</h4>

        @can('Rapid Test: create')
        <!-- <a class="btn btn-primary btn-sm" href="{{ route('lab.inmuno_tests.create', 'search_false') }}">
                <i class="fas fa-plus"></i> Agregar Test
                </a> -->

        <a class="btn btn-primary btn-sm" href="" data-toggle="modal" data-target="#exampleModal">
            <i class="fas fa-plus"></i> Agregar Test
        </a>

        @include('patients.modals.create_rapidtest')

        @endcan
        <div class="table-responsive-sm">
            <table class="table table-sm table-bordered small mb-4 mt-4">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Tipo de Examen</th>
                        <th>Fecha Examen</th>
                        <th>Valor</th>
                        <th>Fecha de Digitación en Sistema</th>
                        <th>Epivigila</th>
                        <th>Observación</th>
                        @can('Rapid Test: delete')
                        <th>Eliminar</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach($patient->rapidTests as $rapidTest)
                    <tr>
                        <td>
                            <a href="#">
                                {{ $rapidTest->id }}
                            </a>
                        </td>
                        <td>{{ ($rapidTest->type) }}</td>
                        <td>{{ $rapidTest->register_at }}</td>
                        <td>{{ ($rapidTest->value_test) }}</td>
                        <td>{{ $rapidTest->created_at }}</td>
                        <td>{{ $rapidTest->epivigila }}</td>
                        <td>{{ $rapidTest->observation??'' }}</td>
                        @can('Rapid Test: delete')
                        <td>                        
                            <form method="POST" class="form-horizontal" action="{{ route('lab.rapid_tests.destroy',$rapidTest) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro que desea eliminar al test rápido del paciente : {{$patient->fullName}}? ' )">
                                    Borrar
                                </button>
                            </form>
                        </td>
                        @endcan
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endcan


@canany(['SanitaryResidence: survey','Developer'])
<hr>

<div class="card">
    <div class="card-body">
        <h4 class="mt-3">Evaluación Residencia Sanitaria </h4>

        <a class="btn btn-primary btn-sm" href="{{ route('sanitary_residences.admission.create', $patient) }}" onclick="return confirm('Recuerde que tiene que llenar los datos de contactos si es que tuviese antes de proceder con la encuesta' )">
            <i class="fas fa-plus"></i> Nueva Encuesta
        </a>
        <div class="table-responsive-sm">
            <table class="table table-sm table-bordered small mb-4 mt-4">
                <thead>
                    <tr class="text-center">
                        <th>Fecha de Encuesta</th>
                        <th>Fecha Digitación en Sistema</th>
                        <th>Encuesta Digitada por</th>
                        <th>¿Es Posible Aislar al Paciente?</th>
                        <th>Resultado Encuesta</th>
                        <th>Resultado Final</th>
                        <th>Ver/Editar Encuesta</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patient->admissionSurvey as $admission)
                    <tr>
                        <td class="text-center align-middle">{{ $admission->created_at->format('d-m-y H:i') }}</td>
                        <td class="text-center align-middle">{{ $admission->updated_at->format('d-m-y H:i') }}</td>
                        <td class="text-center align-middle">{{ $admission->user->name }}</td>
                        <td class="text-center align-middle">{{ $admission->isolate_text }}</td>
                        <td class="text-center align-middle">{!! $admission->result !!}</td>
                        <td class="text-center align-middle">{{ $admission->status }}</td>
                        @if($admission->status)
                        <td class="text-center align-middle">
                            <a class="btn btn-warning btn-sm" href="{{ route('sanitary_residences.admission.show', $admission) }}">
                                <i class="fas fa-poll-h"></i> Ver Encuesta (tiene resultado final)
                            </a>
                        </td>
                        @else

                        <td class="text-center align-middle"><a class="btn btn-success btn-sm" href="{{ route('sanitary_residences.admission.edit', $admission) }}">
                                <i class="fas fa-poll-h"></i> Ver/Editar Encuesta
                            </a></td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endcan('Developer')

<hr>

@can('Admin')

@include('partials.audit', ['audits' => $patient->audits] )

<table class="table table-sm small text-muted mt-3">
    <thead>
        <tr>
            <th>Modelo</th>
            <th>Fecha</th>
            <th>Usuario</th>
            <th>Modificaciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($patient->logs->sortByDesc('created_at') as $log)
        <tr>
            <td>{{ $log->model_type }}</td>
            <td>{{ $log->created_at }}</td>
            <td>{{ $log->user->name }}</td>
            <td>
                @foreach($log->diferencesArray as $key => $diference)
                {{ $key }} => {{ $diference}} <br>
                @endforeach
            </td>
        </tr>
        @endforeach
        @if($patient->demographic)
        @foreach($patient->demographic->logs as $log)
        <tr>
            <td>{{ $log->model_type }}</td>
            <td>{{ $log->created_at }}</td>
            <td>{{ $log->user->name }}</td>
            <td>
                @foreach($log->diferencesArray as $key => $diference)
                {{ $key }} => {{ $diference}} <br>
                @endforeach
            </td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
@endcan

<hr>

@include('patients.partials.timeline', ['timeline' => $timeline])

@endsection

@section('custom_js')
<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-select.min.css') }}">

<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('js/defaults-es_CL.min.js') }}"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-core.js" type="text/javascript" charset="utf-8"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-service.js" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript">
    jQuery(document).ready(function() {

        var iRegion = 0;

        //si es que existe, se carga la region y comuna
        @if($patient->demographic != null)
        var htmlComuna = '<option value="sin-comuna">Seleccione comuna</option><option value="sin-comuna">--</option>';
        var valorRegion = '{{$patient->demographic->region_id}}';
        @foreach($communes as $key => $commune)
        if (valorRegion == '{{$commune->region_id}}') {
            if ('{{$patient->demographic->commune_id}}' == '{{$commune->id}}') {
                htmlComuna = htmlComuna + '<option selected value="' + '{{$commune->id}}' + '">' + '{{$commune->name}}' + '</option>';
            } else {
                htmlComuna = htmlComuna + '<option value="' + '{{$commune->id}}' + '">' + '{{$commune->name}}' + '</option>';
            }
        }
        @endforeach
        jQuery('#comunas').html(htmlComuna);
        @endif

        // caso cuando se cambie manualmente
        jQuery('#regiones').change(function() {
            // var iRegiones = 0;
            var valorRegion = jQuery(this).val();
            var htmlComuna = '<option value="sin-comuna">Seleccione comuna</option><option value="sin-comuna">--</option>';
            @foreach($communes as $key => $commune)
            if (valorRegion == '{{$commune->region_id}}') {
                htmlComuna = htmlComuna + '<option value="' + '{{$commune->id}}' + '">' + '{{$commune->name}}' + '</option>';
            }
            @endforeach
            jQuery('#comunas').html(htmlComuna);
        });

        //obtener coordenadas
        jQuery('.geo').change(function() {
            // Instantiate a map and platform object:
            var platform = new H.service.Platform({
                'apikey': '{{ env('
                API_KEY_HERE ') }}'
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

        //Run y otra identificación excluyentes
        $("#for_other_identification").click(function() {
            $("#for_run").val("");
            $("#for_dv").val("");
            $("#for_run").attr('readonly', 'readonly');
            $("#for_other_identification").removeAttr('readonly', 'readonly');
            $("#for_run").removeAttr('required')
            $("#for_other_identification").attr('required', 'required');
        })

        $("#for_run").click(function() {
            $("#for_other_identification").val("");
            $("#for_other_identification").attr('readonly', 'readonly');
            $("#for_run").removeAttr('readonly', 'readonly');
            $("#for_other_identification").removeAttr('required')
            $("#for_run").attr('required', 'required');
        })

    });
</script>

<link rel="stylesheet" type="text/css" href="{{ asset('assets/timeline/css/timeline.css') }}">
{{-- <script src="{{ asset('assets/timeline/js/timeline.js') }}"></script> --}}

@endsection