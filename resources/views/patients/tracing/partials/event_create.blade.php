<form method="POST" class="form-horizontal" action="{{ route('patients.tracings.events.store')}}">
    @csrf
    @method('POST')

    <div class="form-row">
        <fieldset class="form-group col-md-3">
            <label for="for_event_at">Fecha</label>
            <input type="datetime-local" class="form-control" name="event_at"
                id="for_event_at" required>
        </fieldset>

        <fieldset class="form-group col-md-3">
            <label for="for_event_type_id">Tipo de evento</label>
            <select name="event_type_id" id="for_event_type_id" required class="form-control">
                <option value=""></option>
                @foreach($event_types as $type)
                <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </fieldset>

        <fieldset class="form-group col-md-6">
            <label for="for_details">Detalle del envento</label>
            <textarea class="form-control" name="details" rows="5"
                id="for_details"></textarea>
        </fieldset>

    </div>

    <input type="hidden" class="form-control" name="tracing_id"
        id="for_tracing_id" required value="{{ $patient->tracing->id }}">

    <button type="submit" class="btn btn-primary">Crear</button>

</form>
