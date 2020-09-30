<form method="POST" class="form-horizontal" action="{{ route('patients.tracings.events.store')}}">
    @csrf
    @method('POST')

    <div class="row">
        <div class="col">
            <div class="form-row">
                <fieldset class="form-group col-md-6">
                    <label for="for_event_at">Fecha *</label>
                    <input type="datetime-local" class="form-control" name="event_at"
                        id="for_event_at" required value="{{ date('Y-m-d\TH:i:s') }}">
                </fieldset>

                <fieldset class="form-group col-md-6">
                    <label for="for_event_type_id">Tipo contacto *</label>
                    <select name="contact_type" id="for_contact_type" required class="form-control">
                        <option value=""></option>
                        <option value = 'llamada'> Llamada Telefónica </option>
                        <option value = 'visita'> Visita </option>

                    </select>
                </fieldset>

                <fieldset class="form-group col-md-6">
                    <label for="for_event_type_id">Tipo de evento *</label>
                    <select name="event_type_id" id="for_event_type_id" required class="form-control">
                        <option value=""></option>
                        @foreach($event_types as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </fieldset>

            </div>

            <div class="form-row">
                <fieldset class="form-group col">
                    <label for="for_sympthoms">Síntomas</label>
                    <select name="sympthoms_array[]" id="for_sympthoms"
                        class="form-control selectpicker"
                        title="Seleccione síntomas" multiple >
                        @foreach($symptoms as $symptom)
                        <option value="{{ $symptom->name }}">{{ $symptom->name }}</option>
                        @endforeach
                    </select>
                </fieldset>
            </div>

            <div class="form-row">
                <fieldset class="form-group col-md-12">
                    <label for="for_event_type_id">Próximo seguimiento</label>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="next_action"
                            id="for_next_action1" value="1">
                        <label class="form-check-label" for="inlineRadio1">Mañana</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="next_action"
                            id="for_next_action2" value="2">
                        <label class="form-check-label" for="inlineRadio2">Pasado mañana</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="next_action"
                            id="for_next_action3" value="3">
                        <label class="form-check-label" for="inlineRadio2">En 3 días más</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="next_action"
                            id="for_next_action0" value="0" >
                        <label class="form-check-label" for="inlineRadio3">Dejar de seguir</label>
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="col">
            <div class="form-row">

                <fieldset class="form-group col-md-12">
                    <label for="for_details">Detalle del evento</label>
                    <textarea class="form-control" name="details" rows="8"
                        id="for_details"></textarea>
                </fieldset>

            </div>
        </div>
    </div>


    <input type="hidden" class="form-control" name="tracing_id"
        id="for_tracing_id" required value="{{ $patient->tracing->id }}">

    <button type="submit" class="btn btn-primary">Guardar</button>

</form>
