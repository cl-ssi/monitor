<div class="form-row">

  <fieldset class="form-group col-md">
      <label for="for_street_type">Vìa de residencia</label>
      <select name="street_type" id="for_street_type" class="form-control">
          <option value="Calle" {{($patient->street_type == 'Calle')?'selected':''}}>Calle</option>
          <option value="Pasaje" {{($patient->street_type == 'Pasaje')?'selected':''}}>Pasaje</option>
          <option value="Avenida" {{($patient->street_type == 'Avenida')?'selected':''}}>Avenida</option>
          <option value="Camino" {{($patient->street_type == 'Camino')?'selected':''}}>Camino</option>
      </select>
  </fieldset>

</div>

<div class="form-row">

    <fieldset class="form-group col-4">
        <label for="for_address">Dirección</label>
        <input type="text" class="form-control" name="address" id="for_address"  value="{{ $patient->demographic->address }}">
    </fieldset>

    <fieldset class="form-group col-4">
        <label for="for_number">Número</label>
        <input type="text" class="form-control" name="number" id="for_number" value="{{ $patient->demographic->number }}">
    </fieldset>

    <fieldset class="form-group col-4">
        <label for="for_deparment">Departamento</label>
        <input type="text" class="form-control" name="deparment" id="for_deparment" value="{{ $patient->demographic->deparment }}">
    </fieldset>

</div>

<div class="form-row">

  <fieldset class="form-group col-4">
      <label for="regiones">Región</label>
      <select class="form-control" name="region" id="regiones" value="{{ $patient->demographic->region }}"></select>
  </fieldset>

  <fieldset class="form-group col-4">
      <label for="for_commune">Comuna</label>
      <select class="form-control" name="commune" id="comunas" value="{{ $patient->demographic->comunas }}"></select>
  </fieldset>

  <fieldset class="form-group col-4">
      <label for="for_town">Población/Villa</label>
      <input type="text" class="form-control" name="town" id="for_town" value="{{ $patient->demographic->town }}">
  </fieldset>

</div>

<div class="form-row">

    <fieldset class="form-group col-6">
        <label for="for_latitude">Latitud</label>
        <input type="number" step="00.00000001" class="form-control" name="latitude" id="for_latitude" value="{{ $patient->demographic->latitude }}">
    </fieldset>

    <fieldset class="form-group col-6">
        <label for="for_longitude">Longitud</label>
        <input type="number" step="00.00000001" class="form-control" name="longitude" id="for_longitude" value="{{ $patient->demographic->longitude }}">
    </fieldset>

</div>

<div class="form-row">

    <fieldset class="form-group col-8">
        <label for="for_email">email</label>
        <input type="email" class="form-control" name="email" id="for_email"
            value="{{ $patient->demographic->email }}">
    </fieldset>

    <fieldset class="form-group col-4">
        <label for="for_telephone">Teléfono</label>
        <input type="text" class="form-control" name="telephone" id="for_telephone"
            value="{{ $patient->demographic->telephone }}">
    </fieldset>

</div>
