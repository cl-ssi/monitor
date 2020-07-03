    <div class="form-row">
        <fieldset class="form-group col-md-2">
            <label for="for_run">Run (sin digito)</label>
            <input type="text" class="form-control" name="run" id="for_run"
                value="{{ $covid19->run }}" disabled>
        </fieldset>

        <fieldset class="form-group col-md-1">
            <label for="for_dv">Digito</label>
            <input type="text" class="form-control" name="dv" id="for_dv"
                value="{{ $covid19->dv }}" disabled>
        </fieldset>

        <fieldset class="form-group col-1 col-md-1">
            <label for="">&nbsp;</label>
            <span class="form-control-plaintext"> </span>
        </fieldset>

        <fieldset class="form-group col-md-2">
            <label for="for_other_identification">Otra identificación</label>
            <input type="text" class="form-control" name="other_identification"
                id="for_other_identification" placeholder="Extranjeros"
                value="{{ $covid19->other_identification }}" disabled>
        </fieldset>

    </div>
    <div class="form-row">

        <fieldset class="form-group col-md-3">
            <label for="for_name">Nombre *</label>
            <input type="text" class="form-control" name="name" id="for_name"
                required value="{{ $covid19->name }}" disabled>
        </fieldset>

        <fieldset class="form-group col-md-2">
            <label for="for_fathers_family">Apellido Paterno *</label>
            <input type="text" class="form-control" name="fathers_family"
                id="for_fathers_family" required value="{{ $covid19->fathers_family }}" disabled>
        </fieldset>

        <fieldset class="form-group col-md-2">
            <label for="for_mothers_family">Apellido Materno</label>
            <input type="text" class="form-control" name="mothers_family"
                id="for_mothers_family" value="{{ $covid19->mothers_family }}" disabled>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_gender">Genero *</label>
            <select name="gender" id="for_gender" class="form-control" disabled>
                <option value="">{{ $covid19->gender }}</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_birthday">Fecha Nacimiento *</label>
            <input type="date" class="form-control" id="for_birthday"
                name="birthday" disabled value="{{ $covid19->birthday->format('Y-m-d') }}">
        </fieldset>

    </div>

    <hr>

    <div class="form-row">

        <fieldset class="form-group col-md-3">
            <label for="for_email">Email</label>
            <input type="text" class="form-control" name="email" id="for_email"
                value="{{ $covid19->email }}" disabled>
        </fieldset>

        <fieldset class="form-group col-md-2">
            <label for="for_telephone">Telefono</label>
            <input type="text" class="form-control" name="telephone"
                id="for_telephone" value="{{ $covid19->telephone }}" disabled>
        </fieldset>

        <fieldset class="form-group col-md-4">
            <label for="for_address">Dirección</label>
            <input type="text" class="form-control" name="address"
                id="for_address" value="{{ $covid19->address }}" disabled>
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="regiones_demo">Región *</label>
            <select class="form-control" name="region_id_demo" id="regiones_demo" disabled>
                <option value="">{{$covid19->commune->region->name}}</option>

            </select>
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_commune">Comuna *</label>
            <select name="commune" id="for_commune" class="form-control" disabled>
                <option value="">{{ $covid19->commune->name }}</option>
            </select>
        </fieldset>


    </div>

    <hr>

    <div class="form-row">

        <fieldset class="form-group col-12 col-md-3">
            <label for="regiones">Región Origen *</label>
            <select class="form-control" name="region_id" id="regiones" disabled>
                <option value="">{{ $covid19->establishment->commune->region->name }}</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-3">
            <label for="for_origin_commune">Comuna Origen</label>
            <select name="origin_commune" id="for_origin_commune" class="form-control" disabled>
                <option value="">{{$covid19->establishment->commune->name}}</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-3">
            <label for="for_sample_type">Tipo de Muestra *</label>
            <select name="sample_type" id="for_sample_type" class="form-control" disabled>
                <option value="">{{ $covid19->sample_type }}</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-3">
            <label for="for_sample_at">Fecha de muestra *</label>
            <input type="datetime-local" class="form-control" name="sample_at"
                id="for_sample_at" disabled value="{{ $covid19->sample_at->format('Y-m-d\TH:i:s') }}">
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_run_medic">Run Médico *</label>
            <input type="text" class="form-control" name="run_medic" disabled
                   id="for_run_medic" value="{{$covid19->run_medic}}" >
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_run_responsible">Run Responsable *</label>
            <input type="text" class="form-control" name="run_responsible"
                   id="for_run_responsible" value="{{$covid19->run_responsible}}" disabled>
        </fieldset>
    </div>
