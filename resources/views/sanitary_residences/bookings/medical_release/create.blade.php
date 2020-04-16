<h3 class="mb-3">Dar de Alta a Paciente</h3>

<form method="POST" class="form-horizontal" action="{{ route('sanitary_residences.bookings.store') }}">
    @csrf
    @method('POST')

    <input type="hidden" name="booking_id" value="{{ $booking->id }}">    

    <div class="form-row">
        <fieldset class="form-group col-8 col-md-3">
            <label for="for_real_to">Fecha y Hora de Alta</label>
            <input type="datetime-local" class="form-control" name="real_to" id="for_for_real_to" required>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_status">Estado</label>
            <select name="status" id="for_status" class="form-control">
                <option value="Alta Residencia Sanitaria">Alta</option>
                <option value="Hospitalizado">Hospitalizado</option>
                <option value="Fugado">Fugado</option>
                <option value="Fallecido">Fallecido</option>                
            </select>
        </fieldset>

        <fieldset class="form-group col-12 col-md-5">
            <label for="for_released_cause">Causal de Alta</label>
            <input type="text" class="form-control" name="released_cause" id="for_released_cause" autocomplete="off">
        </fieldset>
    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>
    <a class="btn btn-outline-secondary" href="{{ route('sanitary_residences.bookings.index') }}">Cancelar</a>


</form>