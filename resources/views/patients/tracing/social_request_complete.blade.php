@extends('layouts.app')

@section('title', 'Seguimiento Social')

@section('content')
<h3 class="mb-3">Seguimiento Social</h3>

<div class="form-row">
</div>

<div class="form-row">
    <fieldset class="form-group col-md-2">
        <label>Run:</label>
        <input type="text" class="form-control" value="{{ $tracing_request->tracing->patient->identifier }}" style="text-transform: uppercase;" readonly>
    </fieldset>

    <fieldset class="form-group col-md-5">
        <label for="for_fathers_family">Paciente:</label>
        <input type="text" class="form-control" value="{{ $tracing_request->tracing->patient->fullName }}" style="text-transform: uppercase;" readonly>
    </fieldset>
</div>

<div class="form-row">
    <fieldset class="form-group col-md-2">
        <label>Nro. de Solicitud:</label>
        <input type="text" class="form-control" value="{{ $tracing_request->id }}" style="text-transform: uppercase;" readonly>
    </fieldset>

    <fieldset class="form-group col-md-3">
        <label for="for_sample_at">Fecha Solicitud</label>
        <input type="datetime-local" class="form-control" id="for_sample_at"
            name="sample_at" value="{{ $tracing_request->request_at->format('Y-m-d\TH:i:s') }}" readonly>
    </fieldset>

    <fieldset class="form-group col-md-2">
        <label>Tipo de Solicitud:</label>
        <input type="text" class="form-control" value="{{ $tracing_request->type->name }}" style="text-transform: uppercase;" readonly>
    </fieldset>

    <fieldset class="form-group col-md-9">
        <label for="for_fathers_family">Detalle de Solicitud</label>
        <input type="text" class="form-control" value="{{ $tracing_request->details }}" style="text-transform: uppercase;" readonly>
    </fieldset>
</div>

<hr>

<form method="POST" class="form-horizontal" action="{{ route('patients.tracings.requests.request_complete_update', $tracing_request) }}">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <fieldset class="form-group">
                    <label for="for_validity_at">Fecha Vigencia:</label>
                    <input type="datetime-local" class="form-control" id="for_validity_at" name="validity_at">
                </fieldset>
            </div>

            <div class="form-group">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="for_rejection" name="rejection" value="1">
                <label class="form-check-label" for="for_rejection">
                    Rechazar Solicitud
                </label>
              </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="form-row">
                <fieldset class="form-group col-md-12">
                    <label for="for_request_complete_details">Observacion</label>
                    <textarea class="form-control" name="request_complete_details" rows="2"
                        id="for_request_complete_details" required></textarea>
                </fieldset>
            </div>
        </div>

    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
</form>



@endsection

@section('custom_js')

@endsection
