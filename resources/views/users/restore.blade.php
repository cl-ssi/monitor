@extends('layouts.app')

@section('title', 'Restore Password')

@section('content')
<h3 class="mb-3">Restore Password</h3>

@can('Admin')

<form method="POST" class="form-horizontal" action="{{ route('users.password.store',$user) }}">
    @csrf
    @method('PUT')

    <div class="form-row">

        <fieldset class="form-group col">
            <label for="for_password">Nueva clave</label>
            <input type="text" class="form-control" name="password" id="for_password"
                placeholder="Dejar en blanco para crear una automáticamente">
        </fieldset>

    </div>
    <button type="submit" class="btn btn-primary">Cambiar</button>

</form>

@endcan

@endsection

@section('custom_js')

@endsection
