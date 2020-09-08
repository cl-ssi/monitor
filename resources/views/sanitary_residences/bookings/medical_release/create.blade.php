<h3 class="mb-3">Egreso de Residencia</h3>

<form method="POST" class="form-horizontal" action="{{ route('sanitary_residences.bookings.store') }}">
    @csrf
    @method('POST')

    <input type="hidden" name="booking_id" value="{{ $booking->id }}">

    <div class="form-row">
        <fieldset class="form-group col-8 col-md-3">
            <label for="for_real_to">Fecha y Hora de Egreso de Residencia</label>
            <input type="datetime-local" class="form-control" name="real_to" id="for_for_real_to" max="{{ date('Y-m-d\TH:i:s') }}" required>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_status">Estado</label>
            <select name="status" id="for_status" class="form-control">
                <option value=""></option>
                <option value="Alta">Alta</option>
                <option value="Ambulatorio">Ambulatorio (domiciliario)</option>
                <option value="Fallecido">Fallecido</option>
                <option value="Fugado">Fugado</option>
                <option value="Hospitalizado Básico">Hospitalizado Básico</option>
                <option value="Hospitalizado Medio">Hospitalizado Medio</option>
                <option value="Hospitalizado UTI">Hospitalizado UTI</option>
                <option value="Hospitalizado UCI">Hospitalizado UCI</option>
                <option value="Hospitalizado UCI (Ventilador)">Hospitalizado UCI (Ventilador)</option>
                <option value="Residencia Sanitaria">Residencia Sanitaria</option>                
            </select>
        </fieldset>

        <fieldset class="form-group col-12 col-md-5">
            <label for="for_released_cause">Causal de Egreso</label>
            <input type="text" class="form-control" name="released_cause" id="for_released_cause" autocomplete="off">
        </fieldset>
    </div>

    @canany(['SanitaryResidence: user', 'SanitaryResidence: admin'] )
    <button type="submit" class="btn btn-primary" 
    @if(isset($booking->patient->tracing) && isset($booking->patient->tracing->quarantine_end_at))    
    @if ($booking->patient->tracing->quarantine_end_at > now()))
    onclick="return confirm('¿Está seguro que desea dar de alta al paciente sin haber terminado su Cuarentena?. El Paciente: {{$booking->patient->fullName}} finaliza su cuarentena el {{$booking->patient->tracing->quarantine_end_at->format('d-m-Y')}}  ' )"
    @endif
    @endif
    >
    Guardar</button>
    @endcan

    <a class="btn btn-outline-secondary" href="{{ route('sanitary_residences.home') }}">Cancelar</a>

</form>
