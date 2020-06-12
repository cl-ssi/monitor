@extends('layouts.app')

@section('title', 'Nuevo Comentario')

@section('content')
<h3 class="mb-3">Nuevo Comentario</h3>



<form method="POST" class="form-horizontal" action="{{ route('comentarios.store') }}">
    @csrf
    @method('POST')

    <div class="form-row">

        <fieldset class="form-group col">
            <label for="for_fecha">Fecha del comentario</label>
            <input type="date" class="form-control" name="fecha" id="for_fecha"
                required placeholder="">
        </fieldset>

        <fieldset class="form-group col">
            <label for="for_texto">Texto del comentario</label>
            <input type="text" class="form-control" name="texto" id="for_texto"
                required placeholder="">
        </fieldset>


        <fieldset class="form-group col">
            <label for="for_paso_intermedio">Paso paso_intermedio</label>
            <input type="text" class="form-control" name="paso_intermedio" id="for_paso_intermedio"
                required placeholder="">
        </fieldset>

    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>


</form>

















@endsection

@section('custom_js')

@endsection
