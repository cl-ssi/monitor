
<!--**********************************-->
<div class="form-row">
    <fieldset class="form-group col-12 col-sm-3 col-md-2">
        <label for="for_street_type">Vía de residen.</label>
        <select name="street_type" id="for_street_type" class="form-control">
            <option value="Calle" {{($patient->demographic->street_type == 'Calle')?'selected':''}}>Calle</option>
            <option value="Pasaje" {{($patient->demographic->street_type == 'Pasaje')?'selected':''}}>Pasaje</option>
            <option value="Avenida" {{($patient->demographic->street_type == 'Avenida')?'selected':''}}>Avenida</option>
            <option value="Camino" {{($patient->demographic->street_type == 'Camino')?'selected':''}}>Camino</option>
        </select>
    </fieldset>

    <fieldset class="form-group col-9 col-sm-6 col-md-4 geo">
        <label for="for_address">Dirección</label>
        <input type="text" class="form-control" name="address" id="for_address"
            value="{{ $patient->demographic->address }}">
    </fieldset>

    <fieldset class="form-group col-3 col-sm-3 col-md-2 geo">
        <label for="for_number">Número</label>
        <input type="text" class="form-control" name="number" id="for_number"
            value="{{ $patient->demographic->number }}">
    </fieldset>

    <fieldset class="form-group col-3 col-sm-3 col-md-1">
        <label for="for_department">Depto.</label>
        <input type="text" class="form-control" name="department" id="for_department"
            value="{{ $patient->demographic->department }}">
    </fieldset>

    <fieldset class="form-group col-9 col-sm-9 col-md-3">
        <label for="for_suburb">Población/Villa</label>
        <input type="text" class="form-control" name="suburb" id="for_suburb"
            value="{{ $patient->demographic->suburb }}">
    </fieldset>
</div>
<!--**********************************-->
<div class="form-row">
    <fieldset class="form-group col-12 col-sm-6 col-md-2">
        <label for="nationality">Nacionalidad</label>
        <select class="form-control" name="nationality" id="nationality">
            @foreach($countries as $country)
                <option value="{{$country->name}}" {{ ($country->name == $patient->demographic->nationality)? 'selected' : '' }} >{{$country->name}}</option>
            @endforeach
        </select>
    </fieldset>

    <fieldset class="form-group col-12 col-sm-6 col-md-3">
        <label for="regiones">Región</label>
        {{-- <select class="form-control" name="region" id="regiones"
            value="{{ $patient->demographic->region }}"></select> --}}
        <select class="form-control" name="region_id" id="regiones">
          <option>Seleccione Región</option>
          @foreach ($regions as $key => $region)
            <option value="{{$region->id}}" {{($region->id == $patient->demographic->region_id)?'selected':''}}>{{$region->name}}</option>
          @endforeach
        </select>
    </fieldset>

    <fieldset class="form-group col-12 col-sm-6 col-md-3">
        <label for="for_commune geo">Comuna</label>
        <select class="form-control" name="commune_id" id="comunas"
            value="{{ $patient->demographic->comunas }}"></select>
    </fieldset>

    <fieldset class="form-group col-6 col-sm-3 col-md-2">
        <label for="for_latitude">Latitud</label>
        <input type="number" class="form-control" step="00.00000001" id="for_latitude"
            name="latitude" value="{{ $patient->demographic->latitude }}">
    </fieldset>

    <fieldset class="form-group col-6 col-sm-3 col-md-2">
        <label for="for_longitude">Longitud</label>
        <input type="number" step="00.00000001" class="form-control" id="for_longitude"
            name="longitude" value="{{ $patient->demographic->longitude }}">
    </fieldset>
</div>
<!--**********************************-->

<div class="form-row">
    <fieldset class="form-group col-12 col-sm-8 col-md-4 col-lg-3">
        <label for="for_city">Ciudad/Pueblo/Localidad</label>
        <input type="city" class="form-control" name="city" id="for_city"
            value="{{ $patient->demographic->city }}">
    </fieldset>

    <fieldset class="form-group col-12 col-sm-7 col-md-5 col-lg-4">
        <label for="for_email">email</label>
        <input type="email" class="form-control" name="email" id="for_email"
            value="{{ $patient->demographic->email }}" style="text-transform: lowercase;">
    </fieldset>

    <fieldset class="form-group col-12 col-sm-5 col-md-3">
        <label for="for_telephone">Teléfono</label>
        <input type="text" class="form-control" name="telephone" id="for_telephone"
            value="{{ $patient->demographic->telephone }}">
    </fieldset>

</div>
<!--**********************************-->
