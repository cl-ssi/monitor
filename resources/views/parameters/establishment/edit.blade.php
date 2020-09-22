@extends('layouts.app')

@section('title', 'Editar Establecimiento')

@section('content')
@include('parameters.nav')


<h3 class="mb-3">Editar Establecimiento</h3>
<form method="POST" class="form-horizontal" action="{{route('parameters.establishment.update',$establishment)}}">
    @csrf
    @method('PUT')
    <div class="form-row">
        <fieldset class="form-group col-6 col-md-4">
            <label for="for_name">Nombre</label>
            <input type="text" class="form-control" name="name" id="for_name" required placeholder="" autocomplete="off" value="{{$establishment->name}}">
        </fieldset>

        <fieldset class="form-group col-6 col-md-4">
            <label for="for_alias">Alias</label>
            <input type="text" class="form-control" name="alias" id="for_alias" required placeholder="" autocomplete="off" value="{{$establishment->alias}}">
        </fieldset>

        <fieldset class="form-group col-6 col-md-4">
            <label for="for_type">Tipo</label>
            <input type="text" class="form-control" name="type" id="for_type" required placeholder="" autocomplete="off" value="{{$establishment->type}}">
        </fieldset>

        <fieldset class="form-group col-3 col-md-2">
            <label for="for_old_code_deis">Viejo Código DEIS</label>
            <input type="text" class="form-control" name="old_code_deis" id="for_old_code_deis" maxlength="6" placeholder="" autocomplete="off" value="{{$establishment->old_code_deis}}">
        </fieldset>

        <fieldset class="form-group col-3 col-md-2">
            <label for="for_new_code_deis">Nuevo Código DEIS</label>
            <input type="text" class="form-control" name="new_code_deis" id="for_new_code_deis" maxlength="6" placeholder="" required autocomplete="off" value="{{$establishment->new_code_deis}}">
        </fieldset>

        <fieldset class="form-group col-6 col-md-4">
            <label for="for_service">Servicio</label>
            <input type="text" class="form-control" name="service" id="for_service"  placeholder="" required autocomplete="off" value="{{$establishment->service}}">
        </fieldset>

        <fieldset class="form-group col-6 col-md-4">
            <label for="for_dependency">Dependencia</label>
            <input type="text" class="form-control" name="dependency" id="for_service"  placeholder="" required autocomplete="off" value="{{$establishment->dependency}}">
        </fieldset>

        <fieldset class="form-group col-6 col-md-4">
            <label for="for_address">Dirección</label>
            <input type="text" class="form-control" name="address" id="for_address"  placeholder="Ej: Arturo Prat 123" required autocomplete="off" value="{{$establishment->address}}">
        </fieldset>

        <fieldset class="form-group col-6 col-md-4">
            <label for="for_telephone">Teléfono</label>
            <input type="text" class="form-control" name="telephone" id="for_telephone"  placeholder="" required autocomplete="off" value="{{$establishment->telephone}}">
        </fieldset>

        <fieldset class="form-group col-6 col-md-4">
            <label for="for_email">E-mail</label>
            <input type="email" class="form-control" name="email" id="for_email"  placeholder="" required autocomplete="off" value="{{$establishment->email}}">
        </fieldset>

        <fieldset class="form-group col-6 col-md-4">
            <label for="for_commune_id">Comuna</label>
            <select class="form-control" name="commune_id" id="for_commune_id" required>
                <option value="">Seleccione Comuna</option>
                @foreach($communes as $commune)                
                <option value="{{ $commune->id }}" {{($establishment->commune_id == $commune->id) ? 'selected': ''}}>{{ $commune->name }}</option>
                @endforeach
            </select>            
        </fieldset>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a class="btn btn-outline-secondary" href="{{ route('parameters.establishment') }}">Cancelar</a>


</form>

@endsection

@section('custom_js')

@endsection