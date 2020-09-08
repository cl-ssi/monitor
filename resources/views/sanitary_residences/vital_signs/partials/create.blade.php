<h3 class="mb-3">Crear Signos Vitales</h3>

<form method="POST" class="form-horizontal" action="{{ route('sanitary_residences.vital_signs.store') }}">
    @csrf
    @method('POST')

    <input type="hidden" name="booking_id" value="{{ $booking->id }}">
    <input type="hidden" name="vitalsign_id" id="for_id">

    <div class="form-row">



        <fieldset class="form-group col-8 col-md-3">
            <label for="for_created_at">Fecha y Hora Chequeo</label>
            <input type="datetime-local" class="form-control" name="created_at" id="for_created_at" min="{{ date('Y-m-d\TH:i', strtotime('-1 day')) }}" max="{{ date('Y-m-d\TH:i:s') }}" required>
        </fieldset>


        <fieldset class="form-group col-4 col-md-1">
            <label for="for_temperature">Temp.</label>
            <input type="text" class="form-control" name="temperature" id="for_temperature" required autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-4 col-md-1">
            <label for="for_heart_rate">Frec. Card.</label>
            <input type="text" class="form-control" name="heart_rate" id="for_heart_rate"  autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-4 col-md-1">
            <label for="for_blood_pressure">P. Arterial</label>
            <input type="text" class="form-control" name="blood_pressure" id="for_blood_pressure"  autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-4 col-md-1">
            <label for="for_respiratory_rate">Frec. Resp.</label>
            <input type="text" class="form-control" name="respiratory_rate" id="for_respiratory_rate"  autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-4 col-md-1">
            <label for="for_oxygen_saturation">Sat 02</label>
            <input type="text" class="form-control" name="oxygen_saturation" id="for_oxygen_saturation"  autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-4 col-md-1">
            <label for="for_hgt">HGT</label>
            <input type="text" class="form-control" name="hgt" id="for_hgt"  autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-4 col-md-1">
            <label for="for_pain_scale">Escala Dolor</label>
            <input type="text" class="form-control" name="pain_scale" id="for_pain_scale"  autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-12 col-md-5">
            <label for="for_observations">Observaciones</label>
            <input type="text" class="form-control" name="observations" id="for_observations2"  autocomplete="off">
        </fieldset>
    </div>
    @canany(['SanitaryResidence: user', 'SanitaryResidence: admin'] )
    <button type="submit" class="btn btn-primary">Guardar</button>
    @endcan

    <a class="btn btn-outline-secondary" href="{{ route('sanitary_residences.home') }}">Cancelar</a>


</form>
