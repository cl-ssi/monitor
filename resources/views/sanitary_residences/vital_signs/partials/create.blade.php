<h3 class="mb-3">Crear Signos Vitales</h3>

<form method="POST" class="form-horizontal" action="{{ route('sanitary_residences.vital_signs.store') }}">
    @csrf
    @method('POST')

    <input type="hidden" name="booking_id" value="{{ $booking->id }}">

    <div class="form-row">
        <fieldset class="form-group col-4 col-md-1">
            <label for="for_temperature">Temperatura</label>
            <input type="text" class="form-control" name="temperature" id="for_temperature" required autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-4 col-md-2">
            <label for="for_heart_rate">Frecuencia Cardiaca</label>
            <input type="text" class="form-control" name="heart_rate" id="for_heart_rate" required autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-4 col-md-2">
            <label for="for_blood_pressure">Presión Arterial</label>
            <input type="text" class="form-control" name="blood_pressure" id="for_blood_pressure" required autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-4 col-md-2">
            <label for="for_respiratory_rate">Frec. Respiratoria</label>
            <input type="text" class="form-control" name="respiratory_rate" id="for_respiratory_rate" required autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-4 col-md-1">
            <label for="for_oxygen_saturation">Sat 02</label>
            <input type="text" class="form-control" name="oxygen_saturation" id="for_oxygen_saturation" required autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-4 col-md-1">
            <label for="for_hgt">HGT</label>
            <input type="text" class="form-control" name="hgt" id="for_hgt" required autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-4 col-md-1">
            <label for="for_pain_scale">Escala Dolor</label>
            <input type="text" class="form-control" name="pain_scale" id="for_pain_scale" required autocomplete="off">
        </fieldset>
    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>
    <a class="btn btn-outline-secondary" href="{{ route('sanitary_residences.vital_signs.index') }}">Cancelar</a>


</form>
