<h3 class="mb-3">Egreso de Residencia</h3>

<form method="POST" class="form-horizontal" action="{{ route('sanitary_residences.bookings.store') }}">
    @csrf
    @method('POST')

    <input type="hidden" name="booking_id" value="{{ $booking->id }}">    

    <div class="form-row">
        <fieldset class="form-group col-8 col-md-3">
            <label for="for_real_to">Fecha y Hora de Egreso de Residencia</label>
            <input type="datetime-local" class="form-control" name="real_to" id="for_for_real_to" required>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_status">Estado</label>
            <select name="status" id="for_status" class="form-control">
                <option value=""></option>
                <option value="Hospitalizado Básico">Hospitalizado Básico</option>
                <option value="Hospitalizado Crítico">Hospitalizado Crítico</option>
                <option value="Alta">Alta</option>
                <option value="Fallecido">Fallecido</option>
                <option value="Ambulatorio">Ambulatorio (domiciliario)</option>
                <option value="Residencia Sanitaria">Residencia Sanitaria</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-12 col-md-5">
            <label for="for_released_cause">Causal de Egreso</label>
            <input type="text" class="form-control" name="released_cause" id="for_released_cause" autocomplete="off">
        </fieldset>
    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>
    <a class="btn btn-outline-secondary" href="{{ route('sanitary_residences.bookings.index') }}">Cancelar</a>


</form>