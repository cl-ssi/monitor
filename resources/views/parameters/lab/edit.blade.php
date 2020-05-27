@extends('layouts.app')

@section('title', 'Editar Laboratorio')

@section('content')


<h3 class="mb-3">Editar Laboratorio</h3>

<form method="POST" class="form-horizontal" action="{{ route('parameters.lab.update',$laboratory) }}">
    @csrf
    @method('PUT')
    <div class="form-row">
        <fieldset class="form-group col-4">
            <label for="for_name">Nombre</label>
            <input type="text" class="form-control" name="name" id="for_name" required 
                placeholder="" autocomplete="off" value ="{{$laboratory->name}}">
        </fieldset>

        <fieldset class="form-group col">
            <label for="for_commune">Comuna</label>            
            <select class="form-control" name="commune" id="for_commune" required>
                <option value="">Seleccione Comuna</option>
                @foreach($communes as $commune)
                <option value="{{ $commune->id }}" {{($laboratory->commune_id == $commune->id) ? 'selected': ''}}>{{ $commune->name }}</option>
                @endforeach
                
            </select>
        </fieldset>

    </div>

    <div class="form-row">
        <fieldset class="form-group col-2">
            <label for="for_external">Externo </label>
            <select class="form-control" name="external" id="for_external" required>
                <option value="">Seleccione Opción</option>
                <option value="1" {{ ($laboratory->external == '1')?'selected':'' }} >Sí</option>
                <option value="0" {{ ($laboratory->external == '0')?'selected':'' }}>No</option>
            </select>
        </fieldset>

    </div>


    <button type="submit" class="btn btn-primary">Guardar</button>

    <a class="btn btn-outline-secondary" href="{{ route('parameters.lab') }}">Cancelar</a>

</form>


@endsection

@section('custom_js')

@endsection