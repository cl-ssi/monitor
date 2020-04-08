<div class="form-row">

    <fieldset class="form-group col-12 col-md-2">
        <label for="for_street_type">Vía de residencia</label>
        <select name="street_type" id="for_street_type" class="form-control">
            <option value="Calle" {{($patient->demographic->street_type == 'Calle')?'selected':''}}>Calle</option>
            <option value="Pasaje" {{($patient->demographic->street_type == 'Pasaje')?'selected':''}}>Pasaje</option>
            <option value="Avenida" {{($patient->demographic->street_type == 'Avenida')?'selected':''}}>Avenida</option>
            <option value="Camino" {{($patient->demographic->street_type == 'Camino')?'selected':''}}>Camino</option>
        </select>
    </fieldset>

    <fieldset class="form-group col-12 col-md-4">
        <label for="for_address">Dirección</label>
        <input type="text" class="form-control" name="address" id="for_address"
            value="{{ $patient->demographic->address }}">
    </fieldset>

    <fieldset class="form-group col-6 col-md-2">
        <label for="for_number">Número</label>
        <input type="text" class="form-control" name="number" id="for_number"
            value="{{ $patient->demographic->number }}">
    </fieldset>

    <fieldset class="form-group col-6 col-md-1">
        <label for="for_deparment">Depto.</label>
        <input type="text" class="form-control" name="department" id="for_deparment"
            value="{{ $patient->demographic->department }}">
    </fieldset>

    <fieldset class="form-group col-12 col-md-3">
        <label for="for_town">Población/Villa</label>
        <input type="text" class="form-control" name="town" id="for_town"
            value="{{ $patient->demographic->town }}">
    </fieldset>

</div>

<div class="form-row">

    <fieldset class="form-group col-6 col-md-4">
        <label for="regiones">Región</label>
        <select class="form-control" name="region" id="regiones"
            value="{{ $patient->demographic->region }}"></select>
    </fieldset>

    <fieldset class="form-group col-6 col-md-4">
        <label for="for_commune">Comuna</label>
        <select class="form-control" name="commune" id="comunas"
            value="{{ $patient->demographic->comunas }}"></select>
    </fieldset>

    <fieldset class="form-group col-6 col-md-2">
        <label for="for_latitude">Latitud</label>
        <input type="number" class="form-control" step="00.00000001" id="for_latitude"
            name="latitude" value="{{ $patient->demographic->latitude }}">
    </fieldset>

    <fieldset class="form-group col-6 col-md-2">
        <label for="for_longitude">Longitud</label>
        <input type="number" step="00.00000001" class="form-control" id="for_longitude"
            name="longitude" value="{{ $patient->demographic->longitude }}">
    </fieldset>

</div>


<div class="form-row">

    <fieldset class="form-group col-12 col-md-8">
        <label for="for_email">email</label>
        <input type="email" class="form-control" name="email" id="for_email"
            value="{{ $patient->demographic->email }}">
    </fieldset>

    <fieldset class="form-group col-12 col-md-4">
        <label for="for_telephone">Teléfono</label>
        <input type="text" class="form-control" name="telephone" id="for_telephone"
            value="{{ $patient->demographic->telephone }}">
    </fieldset>

</div>
