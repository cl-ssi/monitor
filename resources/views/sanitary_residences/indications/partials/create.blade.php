<h3 class="mb-3">Crear Indicación</h3>

<form method="POST" class="form-horizontal" action="{{ route('sanitary_residences.indications.store') }}">
    @csrf
    @method('POST')

    <input type="hidden" name="booking_id" value="{{ $booking->id }}">
    <input type="hidden" name="indication_id" id="indication_id">

    <div class="form-row">
        <fieldset class="form-group col-12 col-md-3">
            <label for="for_created_at">Fecha y Hora</label>
            <input type="datetime-local" class="form-control" name="created_at" id="for_indication_created_at" max="{{ date('Y-m-d\TH:i:s') }}" required>
        </fieldset>

        <fieldset class="form-group col-12 col-md-9">
            <label for="for_content">Indicación</label>
            <pre><textarea class="form-control" id="for_indication_content" rows="8" name="content"></textarea></pre>
        </fieldset>
    </div>

    @canany(['SanitaryResidence: user', 'SanitaryResidence: admin'] )
    <button type="submit" class="btn btn-primary">Guardar</button>
    @endcan

    <a class="btn btn-outline-secondary" href="{{ route('sanitary_residences.home') }}">Cancelar</a>

</form>
