<form method="POST" class="form-horizontal" action="">
    @csrf
    @method('POST')

    <div class="row">
        <div class="col">
            <div class="form-row">
                <fieldset class="form-group col-md-6">
                    <label for="for_request_at">Fecha de Solicitud*</label>
                    <input type="datetime-local" class="form-control" name="request_at" id="for_event_at" required value="{{ date('Y-m-d\TH:i:s') }}">
                </fieldset>

                <fieldset class="form-group col-md-6">
                    <label for="for_event_type_id">Tipo de solicitud *</label>
                    <select name="event_type_id" id="for_event_type_id" required class="form-control">
                        <option value=""></option>
                        @foreach($event_types as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </fieldset>

            </div>
            <div class="form-row">
                <fieldset class="form-group col-md-6">
                    <label for="for_validity_at">Fecha Vigencia*</label>
                    <input type="datetime-local" class="form-control" name="validity_at" id="for_validity_at" required>
                </fieldset>
            </div>

        </div>
        <div class="col">
            <div class="form-row">

                <fieldset class="form-group col-md-12">
                    <label for="for_details">Detalle del envento</label>
                    <textarea class="form-control" name="details" rows="8"
                        id="for_details"></textarea>
                </fieldset>

            </div>
        </div>
    </div>


    <input type="hidden" class="form-control" name="tracing_id"
        id="for_tracing_id" required value="{{ $patient->tracing->id }}">

    <button type="submit" class="btn btn-primary">Crear</button>

</form>
