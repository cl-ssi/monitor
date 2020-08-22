@extends('layouts.app')

@section('title', 'Crear Laboratorio')

@section('content')
@include('parameters.nav')


<h3 class="mb-3">Crear Establecimiento</h3>
<form method="POST" class="form-horizontal" action="{{ route('parameters.establishment.store') }}">
    @csrf
    @method('POST')
    <div class="form-row">
        <fieldset class="form-group col-6 col-md-4">
            <label for="for_name">Nombre</label>
            <input type="text" class="form-control" name="name" id="for_name" required placeholder="" autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-6 col-md-4">
            <label for="for_alias">Alias</label>
            <input type="text" class="form-control" name="alias" id="for_alias" required placeholder="" autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-6 col-md-4">
            <label for="for_type">Tipo</label>
            <input type="text" class="form-control" name="type" id="for_type" required placeholder="" autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-3 col-md-2">
            <label for="for_old_code_deis">Viejo Código DEIS</label>
            <input type="text" class="form-control" name="old_code_deis" id="for_old_code_deis" maxlength="6" placeholder="" autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-3 col-md-2">
            <label for="for_new_code_deis">Nuevo Código DEIS</label>
            <input type="text" class="form-control" name="new_code_deis" id="for_new_code_deis" maxlength="6" placeholder="" required autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-6 col-md-4">
            <label for="for_service">Servicio</label>
            <input type="text" class="form-control" name="service" id="for_service"  placeholder="" required autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-6 col-md-4">
            <label for="for_dependency">Dependencia</label>
            <input type="text" class="form-control" name="dependency" id="for_service"  placeholder="" required autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-6 col-md-4">
            <label for="for_address">Dirección</label>
            <input type="text" class="form-control" name="address" id="for_address"  placeholder="Ej: Arturo Prat 123" required autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-6 col-md-4">
            <label for="for_telephone">Teléfono</label>
            <input type="text" class="form-control" name="telephone" id="for_telephone"  placeholder="" required autocomplete="off">
        </fieldset>

        <fieldset class="form-group col-6 col-md-4">
            <label for="for_email">E-mail</label>
            <input type="email" class="form-control" name="email" id="for_email"  placeholder="" required autocomplete="off">
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
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>


</form>

@endsection

@section('custom_js')

@endsection