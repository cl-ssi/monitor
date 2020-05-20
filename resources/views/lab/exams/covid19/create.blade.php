@extends('layouts.app')

@section('title', 'Agregar nueva muestra')

@section('content')
<h3 class="mb-3">Agregar nueva muestra</h3>

<form method="POST" class="form-horizontal" action="{{ route('lab.exams.covid19.store') }}">
    @csrf
    @method('POST')

    <div class="form-row">

        <fieldset class="form-group col">
            <label for="for_identifier">Identificador*</label>
            <input type="text" class="form-control" name="identifier"
                id="for_identifier" required>
        </fieldset>

        <fieldset class="form-group col">
            <label for="for_name">Nombre *</label>
            <input type="text" class="form-control" name="name" id="for_name"
                required>
        </fieldset>

        <fieldset class="form-group col">
            <label for="for_fathers_family">Apellido Paterno *</label>
            <input type="text" class="form-control" name="fathers_family"
                id="for_fathers_family" required>
        </fieldset>

        <fieldset class="form-group col">
            <label for="for_mothers_family">Apellido Materno</label>
            <input type="text" class="form-control" name="mothers_family"
                id="for_mothers_family">
        </fieldset>

        <fieldset class="form-group col">
            <label for="for_origin">Origen</label>
            <select name="origin" id="for_origin" class="form-control" readonly>
                <option value="Coquimbo">Coquimbo</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-3">
            <label for="for_sample_at">Fecha de muestra *</label>
            <input type="datetime-local" class="form-control" name="sample_at"
                id="for_sample_at" required value="{{ date('Y-m-d\TH:i:s') }}">
        </fieldset>

    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>


</form>

@endsection

@section('custom_js')

@endsection
