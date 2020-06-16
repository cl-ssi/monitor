@extends('layouts.app')

@section('title', 'Actualizar Estado')

@section('content')
@include('parameters.nav')


<h3 class="mb-3">Actualizar Estado</h3>

<form method="POST" class="form-horizontal" action="{{ route('parameters.statu.update',$statu) }}">
    @csrf
    @method('PUT')
    <div class="form-row">

        <fieldset class="form-group col-12 col-md-4">
            <label for="for_name">Nombre</label>
            <input type="text" class="form-control" name="name" id="for_name" required placeholder="" autocomplete="off" value="{{$statu->name}}">
        </fieldset>

    </div>


    <button type="submit" class="btn btn-primary">Guardar</button>

    <a class="btn btn-outline-secondary" href="{{ route('parameters.statu') }}">Cancelar</a>

</form>


@endsection

@section('custom_js')

@endsection
