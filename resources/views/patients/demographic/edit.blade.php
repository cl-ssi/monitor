<div class="form-row">

    <fieldset class="form-group col-8">
        <label for="for_address">Dirección</label>
        <input type="text" class="form-control" name="address" id="for_address"
            value="{{ $patient->demographic->address }}">
    </fieldset>

    <fieldset class="form-group col-4">
        <label for="for_commune">Comuna</label>
        <input type="text" class="form-control" name="commune" id="for_commune"
            value="{{ $patient->demographic->commune }}">
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
