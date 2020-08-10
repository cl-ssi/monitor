@extends('layouts.app')

@section('title', 'Actualizar Centro de Dialisis')

@section('content')
@include('parameters.nav')


<h3 class="mb-3">Actualizar Centro de Dialisis</h3>

<form method="POST" class="form-horizontal" action="{{ route('parameters.dialysis_center.update', $dialysis_center) }}">
    @csrf
    @method('PUT')
    <div class="form-row">

        <fieldset class="form-group col-6 col-md-4">
            <label for="for_name">Nombre</label>
            <input type="text" class="form-control" name="name" id="for_name" required placeholder="" autocomplete="off" value="{{$dialysis_center->name}}">
        </fieldset>


        <fieldset class="form-group col-6 col-md-4">
            <label for="for_commune_id">Comuna</label>
            <select class="form-control" name="commune_id" id="for_commune_id" required>
                <option value="">Seleccione Comuna</option>
                @foreach($communes as $commune)
                <option value="{{ $commune->id }}" {{($dialysis_center->commune_id == $commune->id) ? 'selected': ''}}>{{ $commune->name }}</option>
                @endforeach

            </select>
        </fieldset>

    </div>


    <button type="submit" class="btn btn-primary">Guardar</button>

    <a class="btn btn-outline-secondary" href="{{ route('parameters.dialysis_center') }}">Cancelar</a>

</form>



@endsection

@section('custom_js')

@endsection
