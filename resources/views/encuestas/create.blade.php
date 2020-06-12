@extends('layouts.app')

@section('title', 'Crear encuesta')

@section('content')
<h3 class="mb-3">Crear encuesta</h3>



<form method="POST" class="form-horizontal" action="{{ route('encuesta.store') }}">
    @csrf
    @method('POST')

    <div class="form-row">
        <fieldset class="form-group col">
            <label for="for_fecha">Fecha</label>
            <input type="date" class="form-control" name="fecha" id="for_fecha"
                required placeholder="">
        </fieldset>

        <fieldset class="form-group col">
            <label for="for_respuesta">Respuesta</label>
            <input type="text" class="form-control" name="respuesta" id="for_respuesta"
                required placeholder="">
        </fieldset>

    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>


</form>




@endsection

@section('custom_js')

@endsection
