<h3 class="mb-3">Egreso de Residencia</h3>

<form method="GET" class="form-horizontal" action="{{ route('sanitary_residences.bookings.store') }}">
    

    <input type="hidden" name="booking_id" value="{{ $booking->id }}">

    <div class="form-row">
        <fieldset class="form-group col-8 col-md-3">
            <label for="for_real_to">Fecha y Hora de Egreso de Residencia</label>
            <input class="form-control" name="real_to" id="for_for_real_to" required value="{{$booking->real_to}}" readonly>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_status">Estado</label>
            <select name="status" id="for_status" class="form-control" readonly>
                <option>{{$booking->status}}</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-12 col-md-5">
            <label for="for_released_cause">Causal de Egreso</label>
            <input type="text" class="form-control" name="released_cause" id="for_released_cause" autocomplete="off" required value="{{$booking->released_cause}}" readonly>
        </fieldset>
    </div>

    
    <a href="{{ URL::previous() }}" class="btn btn-primary"> <i class="fas fa-arrow-left"></i> Volver</a>

</form>
