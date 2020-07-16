<div class="form-row">

    <fieldset class="form-group col-8 col-md-2">
        <label for="for_run">Run</label>
        <input type="text" class="form-control" readonly disabled
            value="{{ $suspectCase->patient->run }}">
    </fieldset>

    <fieldset class="form-group col-4 col-md-1">
        <label for="for_dv">DV</label>
        <input type="text" class="form-control" readonly disabled
            value="{{ $suspectCase->patient->dv }}">
    </fieldset>

    <fieldset class="form-group col-12 col-md-2">
        <label for="for_other_identification">Otra identificación</label>
        <input type="text" class="form-control"
            placeholder="Extranjeros sin run" readonly disabled
            value="{{ $suspectCase->patient->other_identification }}">
    </fieldset>

    <fieldset class="form-group col-6 col-md-2">
        <label for="for_gender">Género</label>
        <select class="form-control" readonly disabled>
            <option value="male"
                {{($suspectCase->patient->gender == 'male')?'selected':''}}>
                Masculino
            </option>
            <option value="female"
                {{($suspectCase->patient->gender == 'female')?'selected':''}}>
                Femenino
            </option>
            <option value="other"
                {{($suspectCase->patient->gender == 'other')?'selected':''}}>
                Otro
            </option>
            <option value="unknown"
                {{($suspectCase->patient->gender == 'unknown')?'selected':''}}>
                Desconocido
            </option>
        </select>
    </fieldset>

    <fieldset class="form-group col-6 col-md-3">
        <label for="for_birthday">Fecha Nacimiento</label>
        <input type="date" class="form-control" readonly disabled
            value="{{ ($suspectCase->patient->birthday)?$suspectCase->patient->birthday->format('Y-m-d'):'' }}">
    </fieldset>

</div>

<div class="form-row">

    <fieldset class="form-group col-12 col-md-4">
        <label for="for_name">Nombres</label>
        <input type="text" class="form-control" readonly disabled
            value="{{ $suspectCase->patient->name }}">
    </fieldset>

    <fieldset class="form-group col-12 col-md-3">
        <label for="for_fathers_family">Apellido Paterno</label>
        <input type="text" class="form-control" readonly disabled
            value="{{ $suspectCase->patient->fathers_family }}">
    </fieldset>

    <fieldset class="form-group col-12 col-md-3">
        <label for="for_mothers_family">Apellido Materno</label>
        <input type="text" class="form-control" readonly disabled
            value="{{ $suspectCase->patient->mothers_family }}">
    </fieldset>

</div>
