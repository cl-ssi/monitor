@extends('layouts.app')

@section('title', 'Cambiar clave')

@section('content')

<h3 class="mb-3">Cambiar clave</h3>

<form method="POST" class="form-horizontal" action="{{ route('users.password.update') }}">
    @csrf
    @method('PUT')

    <div class="form-row">
        <fieldset class="form-group col-6 col-md-3">
            <label for="for_current_password">Password Actual</label>
            <input type="password" class="form-control" name="current_password"
                id="for_current_password" required>
        </fieldset>

        <fieldset class="form-group col-6 col-md-3">
            <label for="for_new_password">Nuevo Password</label>
            <input type="password" class="form-control" name="new_password"
                id="for_new_password" required>
        </fieldset>
    </div>

    <button type="submit" class="btn btn-primary">Cambiar</button>

</form>

@endsection

@section('custom_js')

@endsection
