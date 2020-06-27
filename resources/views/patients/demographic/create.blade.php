<div class="form-row">

    <fieldset class="form-group col-12 col-md-2">
        <label for="for_street_type">Vía de residencia *</label>
        <select name="street_type" id="for_street_type" class="form-control" required>
            <option value=""></option>
            <option value="Calle" {{(old('street_type') == 'Calle') ? 'selected' : '' }} >Calle</option>
            <option value="Pasaje" {{(old('street_type') == 'Pasaje') ? 'selected' : '' }} >Pasaje</option>
            <option value="Avenida" {{(old('street_type') == 'Avenida') ? 'selected' : '' }} >Avenida</option>
            <option value="Camino" {{(old('street_type') == 'Camino') ? 'selected' : '' }} >Camino</option>
        </select>
    </fieldset>


    <fieldset class="form-group col-12 col-md-4 geo">
        <label for="for_address">Dirección *</label>
        <input type="text" class="form-control" name="address" id="for_address" required value="{{old('address')}}">
    </fieldset>

    <fieldset class="form-group col-6 col-md-2 geo">
        <label for="for_number">Número</label>
        <input type="text" class="form-control" name="number" id="for_number" value="{{old('number')}}">
    </fieldset>

    <fieldset class="form-group col-6 col-md-1">
        <label for="for_department">Depto.</label>
        <input type="text" class="form-control" name="department" id="for_department" value="{{old('department')}}">
    </fieldset>

    <fieldset class="form-group col-12 col-md-3">
        <label for="for_suburb">Población/Villa</label>
        <input type="text" class="form-control" name="suburb" id="for_suburb" value="{{old('suburb')}}">
    </fieldset>
</div>

<div class="form-row">

    <fieldset class="form-group col-12 col-md-2">
        <label for="nationality">Nacionalidad *</label>
        <select class="form-control" name="nationality" id="nationality" required>
            @foreach($countries as $country)
                <option value="{{$country->name}}"

                @if(old('nationality') == null)
                    @if($country->name == 'Chile')
                        {{'selected'}}
                    @endif
                @elseif(old('nationality') == $country->name)
                    {{'selected'}}
                @endif

                >{{$country->name}}</option>
            @endforeach
        </select>
    </fieldset>

    <fieldset class="form-group col-12 col-md-3">
        <label for="regiones">Región *</label>
        <select class="form-control" name="region_id" id="regiones">
          <option>Seleccione Región</option>
          @foreach ($regions as $key => $region)
            <option value="{{$region->id}}" {{(old('region_id') == $region->id) ? 'selected' : '' }} >{{$region->name}}</option>
          @endforeach
        </select>
    </fieldset>

    <fieldset class="form-group col-12 col-md-3">
        <label for="comunas">Comuna *</label>
        <select class="form-control geo" name="commune_id" id="comunas" value="{{old('commune_id')}}" required></select>
    </fieldset>

    <fieldset class="form-group col-6 col-md-2">
        <label for="for_latitude">Latitud</label>
        <input type="number" step="00.00000001" class="form-control" name="latitude" id="for_latitude" value="{{old('latitude')}}">
    </fieldset>

    <fieldset class="form-group col-6 col-md-2">
        <label for="for_longitude">Longitud</label>
        <input type="number" step="00.00000001" class="form-control" name="longitude" id="for_longitude" value="{{old('longitude')}}">
    </fieldset>

</div>

<div class="form-row">

    <fieldset class="form-group col-12 col-md-2">
        <label for="for_city">Ciudad/Pueblo/Localidad*</label>
        <input type="text" class="form-control" name="city" id="for_city" value="{{old('city')}}" required>
    </fieldset>

    <fieldset class="form-group col-12 col-md-6">
        <label for="for_email">email</label>
        <input type="email" class="form-control" name="email" id="for_email" value="{{old('email')}}"
        style="text-transform: lowercase;">
    </fieldset>

    <fieldset class="form-group col-12 col-md-4">
        <label for="for_telephone">Teléfono *</label>
        <input type="text" class="form-control" name="telephone" id="for_telephone" value="{{old('telephone')}}" required>
    </fieldset>

</div>
