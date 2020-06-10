@extends('layouts.app')

@section('title', 'Crear Laboratorio')

@section('content')


<h3 class="mb-3">Crear Laboratorio</h3>

<form method="POST" class="form-horizontal" action="{{ route('parameters.lab.store') }}">
    @csrf
    @method('POST')
    <div class="form-row">

        <fieldset class="form-group col-6 col-md-4">
            <label for="for_name">Nombre</label>
            <input type="text" class="form-control" name="name" id="for_name" required placeholder="" autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-6 col-md-4">
            <label for="for_commune_id">Comuna</label>
            <select class="form-control" name="commune_id" id="for_commune_id" required>
                <option value="">Seleccione Comuna</option>
                @foreach($communes as $commune)
                <option value="{{ $commune->id }}">{{ $commune->name }}</option>
                @endforeach
            </select>
        </fieldset>

        <fieldset class="form-group col-3 col-md-2">
            <label for="for_external">Externo</label>
            <select class="form-control" name="external" id="for_external" required>
                <option value="">Seleccione Opción</option>
                <option value="0">No</option>
                <option value="1">Sí</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-2">
            <label for="for_minsal_ws">Webservice Minsal</label>
            <select class="form-control" name="minsal_ws" id="for_minsal_ws" required>
                <option value="">Seleccione Opción</option>
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-4">
            <label for="for_token_ws">Token</label>
            <input type="text" class="form-control" name="token_ws" id="for_token_ws" placeholder="" autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-2">
            <label for="for_pdf_generate">PDF Automático</label>
            <select class="form-control" name="pdf_generate" id="for_pdf_generate" required>
                <option value="">Seleccione Opción</option>
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-2">
            <label for="for_cod_deis">Cod. Deis</label>
            <input type="text" class="form-control" name="cod_deis" id="for_cod_deis" placeholder="" >
        </fieldset>

    </div>


    <button type="submit" class="btn btn-primary">Guardar</button>

    <a class="btn btn-outline-secondary" href="{{ route('parameters.lab') }}">Cancelar</a>

</form>


@endsection

@section('custom_js')

@endsection
