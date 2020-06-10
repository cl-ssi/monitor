@extends('layouts.app')

@section('title', 'Editar Paciente')

@section('content')
<h3 class="mb-3">Editar Paciente</h3>

<form method="POST" class="form-horizontal" action="{{ route('patients.update',$patient) }}">
    @csrf
    @method('PUT')

    <div class="form-row">
        <fieldset class="form-group col-10 col-md-2">
            <label for="for_run">Run</label>
            <input type="text" class="form-control" id="for_run" name="run"
                value="{{ $patient->run }}">
        </fieldset>

        <fieldset class="form-group col-2 col-md-1">
            <label for="for_dv">DV</label>
            <input type="text" class="form-control" id="for_dv" name="dv"
                value="{{ $patient->dv }}">
        </fieldset>

        <fieldset class="form-group col-12 col-md-4">
            <label for="for_other_identification">Otra identificación</label>
            <input type="text" class="form-control" id="for_other_identification"
                placeholder="Extranjeros sin run" name="other_identification"
                value="{{ $patient->other_identification }}">
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_gender">Genero</label>
            <select name="gender" id="for_gender" class="form-control">
                <option value="male"
                    {{($patient->gender == 'male')?'selected':''}}>
                    Masculino
                </option>
                <option value="female"
                    {{($patient->gender == 'female')?'selected':''}}>
                    Femenino
                </option>
                <option value="other"
                    {{($patient->gender == 'other')?'selected':''}}>
                    Otro
                </option>
                <option value="unknown"
                    {{($patient->gender == 'unknown')?'selected':''}}>
                    Desconocido
                </option>
            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_birthday">Fecha Nacimiento</label>
            <input type="date" class="form-control" id="for_birthday" required
                name="birthday" value="{{ ($patient->birthday)?$patient->birthday->format('Y-m-d'):'' }}">
        </fieldset>
    </div>

    <div class="form-row">
        <fieldset class="form-group col-12 col-md-4">
            <label for="for_name">Nombres*</label>
            <input type="text" class="form-control" id="for_name" name="name"
                value="{{ $patient->name }}" style="text-transform: uppercase;"
                required>
        </fieldset>

        <fieldset class="form-group col-12 col-md-4">
            <label for="for_fathers_family">Apellido Paterno*</label>
            <input type="text" class="form-control" id="for_fathers_family"
                name="fathers_family" value="{{ $patient->fathers_family }}" style="text-transform: uppercase;"
                required>
        </fieldset>

        <fieldset class="form-group col-12 col-md-4">
            <label for="for_mothers_family">Apellido Materno</label>
            <input type="text" class="form-control" id="for_mothers_family"
                name="mothers_family" value="{{ $patient->mothers_family }}" style="text-transform: uppercase;">
        </fieldset>

    </div>

    <div class="card mb-3">
        <div class="card-body">

        @if($patient->demographic)
            @include('patients.demographic.edit')
        @else
            @include('patients.demographic.create')
        @endif

        </div>
    </div>

    <div class="form-row">
        <fieldset class="form-group col-12 col-md-3">
            <label for="for_status">Estado</label>
            <select name="status" id="for_status" class="form-control">
                <option value=""></option>
                <option value="Alta" {{ ($patient->status == 'Alta')?'selected':'' }}>Alta</option>
                <option value="Ambulatorio" {{ ($patient->status == 'Ambulatorio')?'selected':'' }}>Ambulatorio (domiciliario)</option>
                <option value="Fallecido" {{ ($patient->status == 'Fallecido')?'selected':'' }}>Fallecido</option>
                <option value="Fugado" {{ ($patient->status == 'Fugado')?'selected':'' }}>Fugado</option>
                <option value="Hospitalizado Básico" {{ ($patient->status == 'Hospitalizado Básico')?'selected':'' }}>Hospitalizado Básico</option>
                <option value="Hospitalizado Medio" {{ ($patient->status == 'Hospitalizado Medio')?'selected':'' }}>Hospitalizado Medio</option>
                <option value="Hospitalizado UTI" {{ ($patient->status == 'Hospitalizado UTI')?'selected':'' }}>Hospitalizado UTI</option>
                <option value="Hospitalizado UCI" {{ ($patient->status == 'Hospitalizado UCI')?'selected':'' }}>Hospitalizado UCI</option>
                <option value="Hospitalizado UCI (Ventilador)" {{ ($patient->status == 'Hospitalizado UCI')?'selected':'' }}>Hospitalizado UCI (Ventilador)</option>
                <option value="Residencia Sanitaria" {{ ($patient->status == 'Residencia Sanitaria')?'selected':'' }}>Residencia Sanitaria</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_deceased_at">Fallecido</label>
            <input type="date" class="form-control" name="deceased_at" id="for_deceased_at"
                value="{{ ($patient->deceased_at) ? $patient->deceased_at->format('Y-m-d') : '' }}">
        </fieldset>

    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>

    <a class="btn btn-outline-secondary" href="{{ route('patients.index') }}">
        Cancelar
    </a>

</form>

@can('Patient: delete')
    @if($patient->suspectCases->count() === 0)
    <form method="POST" class="form-horizontal" action="{{ route('patients.destroy',$patient) }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger float-right">Borrar</button>

    </form>
    @else
        <button class="btn btn-outline-danger float-right" disabled>No es posible eliminar, tiene examenes asociados</button>
    @endif
@endcan


@can('SuspectCase: list')

    <h4 class="mt-4">Examenes realizados</h4>

    <table class="table table-sm table-bordered small mb-4 mt-4">
        <thead>
            <tr>
                <th>Id</th>
                <th>Establecimiento</th>
                <th>Fecha muestra</th>
                <th>Fecha resultado</th>
                <th>Resultado</th>
                <th>Epivigila</th>
                <th>Observacion</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patient->suspectCases as $case)
            <tr>
                <td>
                    <a href="{{ route('lab.suspect_cases.edit', $case )}}">
                    {{ $case->id }}
                    </a>
                </td>
                <td>{{ $case->establishment?$case->establishment->alias.' - '.$case->origin: '' }}</td>
                <td>{{ $case->sample_at }}</td>
                <td>{{ $case->pscr_sars_cov_2_at }}</td>
                <td>{{ $case->covid19 }}</td>
                <td>{{ $case->epivigila }}</td>
                <td>{{ $case->observation }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

@endcan

@can('Inmuno Test: list')

    <h4 class="mt-4">Examenes Inmunoglobulinas</h4>

    <table class="table table-sm table-bordered small mb-4 mt-4">
        <thead>
            <tr>
                <th>Id</th>
                <th>Fecha Examen</th>
                <th>IgG</th>
                <th>IgM</th>
                <th>Control</th>
                <th>Fecha de Carga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patient->inmunoTests as $inmunoTest)
            <tr>
                <td>
                    <a href="{{ route('lab.inmuno_tests.edit', $inmunoTest )}}">
                    {{ $inmunoTest->id }}
                    </a>
                </td>
                <td class="text-right">{{ $inmunoTest->register_at->format('d-m-Y H:i:s') }}</td>
                <td class="text-right">{{ strtoupper($inmunoTest->IgValue) }}</td>
                <td class="text-right">{{ strtoupper($inmunoTest->ImValue) }}</td>
                <td class="text-right">{{ strtoupper($inmunoTest->ControlValue) }}</td>
                <td class="text-right">{{ $inmunoTest->created_at->format('d-m-Y H:i:s') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

@endcan

@can('Admin')

<h4 class="mt-3">Historial de cambios</h4>
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

@endsection

@section('custom_js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-core.js" type="text/javascript" charset="utf-8"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-service.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">

jQuery(document).ready(function () {

	var iRegion = 0;

  //si es que existe, se carga la region y comuna
  @if($patient->demographic != null)
    var htmlComuna = '<option value="sin-comuna">Seleccione comuna</option><option value="sin-comuna">--</option>';
    var valorRegion = '{{$patient->demographic->region_id}}';
    @foreach ($communes as $key => $commune)
      if (valorRegion == '{{$commune->region_id}}') {
        if('{{$patient->demographic->commune_id}}' == '{{$commune->id}}'){
          htmlComuna = htmlComuna + '<option selected value="' + '{{$commune->id}}' + '">' + '{{$commune->name}}' + '</option>';
        }else{
          htmlComuna = htmlComuna + '<option value="' + '{{$commune->id}}' + '">' + '{{$commune->name}}' + '</option>';
        }
      }
    @endforeach
    jQuery('#comunas').html(htmlComuna);
  @endif

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

});
</script>

@endsection
