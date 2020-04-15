<h3 class="mb-3">Crear Evolución</h3>

<form method="POST" class="form-horizontal" action="{{ route('sanitary_residences.evolutions.store') }}">
    @csrf
    @method('POST')

    <input type="hidden" name="booking_id" value="{{ $booking->id }}">
    <input type="hidden" name="evolution_id" id="for_id">

    <div class="form-row">
        <fieldset class="form-group col-12">
            <label for="for_content">Evolución</label>
            <textarea class="form-control" id="for_content" rows="2" name="content"></textarea>
        </fieldset>
    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>
    <a class="btn btn-outline-secondary" href="{{ route('sanitary_residences.bookings.index') }}">Cancelar</a>


</form>
