<form method="POST" class="form-horizontal" action="{{ route('patients.tracings.requests.store') }}">
    @csrf
    @method('POST')

    <div class="row">
        <div class="col">
            <div class="form-row">
                <fieldset class="form-group col-md-6">
                    <label for="for_request_at">Fecha de Solicitud*</label>
                    <input type="datetime-local" class="form-control" name="request_at" id="for_request_at" required value="{{ date('Y-m-d\TH:i:s') }}">
                </fieldset>

                <fieldset class="form-group col-md-6">
                    <label for="for_request_type_id">Tipo de solicitud *</label>
                    <select name="request_type_id" id="for_request_type_id" required class="form-control">
                        <option value=""></option>
                        @foreach($request_types as $type)
                          @if($patient->tracing->index == 1 || $patient->tracing->index == 2)
                          <option value="{{ $type->id }}" @if($type->name == 'Licencia Medica SEREMI') disabled @endif>{{ $type->name }}</option>
                          @else
                          <option value="{{ $type->id }}" @if($type->name == 'Licencia Medica APS') disabled @endif>{{ $type->name }}</option>
                          @endif
                        @endforeach
                    </select>
                </fieldset>
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>

        </div>
        <div class="col">
            <div class="form-row">

                <fieldset class="form-group col-md-12">
                    <label for="for_details">Detalle de la solicitud</label>
                    <textarea class="form-control" name="details" rows="3"
                        id="for_details"></textarea>
                </fieldset>

            </div>
        </div>
    </div>


    <input type="hidden" class="form-control" name="tracing_id"
        id="for_tracing_id" required value="{{ $patient->tracing->id }}">


</form>
