@extends('layouts.app')

@section('title', 'Integración Hetg - Esmeralda - Pendientes')

@section('content')

<h3 class="mb-3">Integración HETG - Esmeralda - Pendientes</h3>

<form method="GET" class="form-horizontal" action="{{ route('lab.suspect_cases.reports.integration_hetg_monitor_pendings') }}">

    <div class="form-row">
        <fieldset class="form-group col-6 col-md-6">
            <label for="for_laboratory">Estado</label>
            <select name="status" class="form-control" onchange="this.form.submit()">
              <option value="case_not_found" {{("case_not_found" == $request->status)?'selected':''}}>Muestra no encontrada</option>
              <option value="too_many_cases" {{("too_many_cases" == $request->status)?'selected':''}}>Muchas muestras encontradas</option>
              <option value="assigned_to_case" {{("assigned_to_case" == $request->status)?'selected':''}}>Muestras asignadas</option>
              <option value="monitor_error" {{("monitor_error" == $request->status)?'selected':''}}>Error monitor</option>
            </select>
        </fieldset>
    </div>
</form>

<small>Filas: {{$hl7ResultMessages->count()}}</small>

<table class="table table-sm table-bordered table-striped small">
	<thead>
		<tr class="text-center">
      <th>ID</th>
      <th>NOMBES</th>
      <th>AP.PATERNO</th>
      <th>AP.MATERNO</th>
      <th>FECHA MUESTRA</th>
      <th>FECHA RESULTADO</th>
      <th>RESULTADO</th>
      <th>ACCIÓN</th>
		</tr>
	</thead>
	<tbody>
    @foreach($hl7ResultMessages as $hl7ResultMessage)
      <tr>
        <td>{{$hl7ResultMessage->id}}</td>
        <td>{{$hl7ResultMessage->patient_names}}</td>
        <td>{{$hl7ResultMessage->patient_family_father}}</td>
        <td>{{$hl7ResultMessage->patient_family_mother}}</td>
        <td>{{$hl7ResultMessage->sample_observation_datetime}}</td>
        <td>{{$hl7ResultMessage->observation_datetime}}</td>
        <td>{{$hl7ResultMessage->observation_value}}</td>
        <td class="text-center">
            <a class="btn btn-primary btn-sm" href="{{ route('lab.suspect_cases.reports.integration_hetg_monitor_pendings_details', $hl7ResultMessage) }}">
              Detalle
            </a>
        </td>
      </tr>
    @endforeach
	</tbody>
</table>

@endsection

@section('custom_js')

@endsection
