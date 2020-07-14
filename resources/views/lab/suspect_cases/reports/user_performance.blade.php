@extends('layouts.app')

@section('title', 'Pacientes con Licencia Medica')

@section('content')
<h3 class="mb-3">Reporte de rendimiento de usuarios:</h3>

<div class="card">
    <div class="card-body">

      <form method="get" class="form-horizontal" action="{{ route('lab.suspect_cases.reports.user_performance') }}">

          <div class="form-row">
              <fieldset class="form-group col-md-3">
                  <label for="for_date">Fecha</label>
                  <input type="date" class="form-control" name="date" id="for_date" value="{{ $request->date }}" required>
              </fieldset>

              <fieldset class="form-group col-md-6">
                  <label for="for_user">Funcionario</label>
                  <select class="form-control selectpicker" name="user" id="for_user" title="Seleccione..." data-live-search="true" data-size="5" required>
                    @foreach($users as $user)
                      <option value="{{ $user->id }}" {{ ($user->id == $request->user)?'selected':'' }}>{{ $user->name }}</option>
                    @endforeach
                  </select>
              </fieldset>
          </div>

          <div>
            <button type="submit" class="btn btn-primary float-right">Consultar</button>
          </div>
      </form>

    </div>
</div>

@if($events->count() > 0)
  <br>

  <div class="table-responsive">
    <table class="table table-sm table-bordered table-striped small">
        <thead>
          <tr class="text-center">
            @foreach($events_resume as $key => $resume)
                <th width="10%">{{ $key }}</th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          <tr class="text-center">
            @foreach($events_resume as $key => $resume)
                <td>{{ $resume }}</td>
            @endforeach
          </tr>
        </tbody>
    </table>
  </div>

  <br>

  <div class="table-responsive">
    <table class="table table-sm table-bordered table-striped small">
      <thead>
        <tr class="text-center">
          <th>#</th>
          <th>Fecha</th>
          <th>Paciente</th>
          <th>Tipo Evento</th>
          <th>Funcionario</th>
        </tr>
      </thead>
      <tbody>
        @foreach($events as $key => $event)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $event->event_at->format('d-m-Y H:i:s') }}</td>
            <td>{{ $event->tracing->patient->fullName }}</td>
            <td>{{ $event->type->name }}</td>
            <td>{{ $event->user->name }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

@else
  <br>

  <div class="alert alert-danger" role="alert">
      No se encontraron registros para la fecha y usuario seleccionado.
  </div>
@endif



@endsection

@section('custom_js')

<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-select.min.css') }}">

<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('js/defaults-es_CL.min.js') }}"></script>


@endsection
