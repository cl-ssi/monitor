@extends('layouts.app')

@section('title', 'Nuevo Usuario')

@section('content')
<h3 class="mb-3">Nuevo Usuario</h3>


<form method="POST" class="form-horizontal" action="{{ route('users.store') }}">
    @csrf
    @method('POST')

    <div class="form-row">
        <fieldset class="form-group col">
            <label for="for_name">Nombre y Apellido</label>
            <input type="text" class="form-control" name="name" id="for_name"
                required>
        </fieldset>

        <fieldset class="form-group col">
            <label for="for_email">Email</label>
            <input type="text" class="form-control" name="email" id="for_email"
                required>
        </fieldset>

        <fieldset class="form-group col">
            <label for="for_password">Clave</label>
            <input type="password" class="form-control" name="password" id="for_password"
                required>
        </fieldset>


    </div>

    @foreach($permissions as $permission)
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="permissions[]"
            value="{{ $permission->name }}">
        <label class="form-check-label">
            {{ $permission->name }}
        </label>
    </div>
    @endforeach

    <button type="submit" class="btn btn-primary">Guardar</button>


</form>



@endsection

@section('custom_js')

@endsection
