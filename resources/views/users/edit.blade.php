@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')

@include('parameters.nav')

<h3 class="mb-3">Editar Usuario</h3>


<form method="POST" class="form-horizontal" action="{{ route('users.update',$user) }}">
    @csrf
    @method('PUT')

    <div class="form-row">

      <fieldset class="form-group col-2">
          <label for="for_run">Run</label>
          <input type="number" class="form-control" name="run" id="for_run" value="{{ $user->run }}">
      </fieldset>

      <fieldset class="form-group col-1">
          <label for="for_dv">Dv</label>
          <input type="text" class="form-control" name="dv" id="for_dv" value="{{ $user->dv }}">
      </fieldset>

      <fieldset class="form-group col">
          <label for="for_name">Nombre</label>
          <input type="text" class="form-control" name="name" id="for_name"
              required value="{{ $user->name }}">
      </fieldset>

      <fieldset class="form-group col">
          <label for="for_email">Email</label>
          <input type="text" class="form-control" name="email" id="for_email"
              required value="{{ $user->email }}">
      </fieldset>

        <fieldset class="form-group col-2">
            <label for="for_laboratory_id">Laboratorio</label>
            <select name="laboratory_id" id="for_laboratory_id" class="form-control">
                <option value=""></option>
                @foreach($laboratories as $lab)
                <option value="{{ $lab->id }}" {{ ($user->laboratory_id == $lab->id)?'selected':'' }}>{{ $lab->name }}</option>
                @endforeach
            </select>
        </fieldset>

    </div>

    @foreach($permissions as $permission)
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="permissions[]"
            value="{{ $permission->name }}" {{ ($user->hasPermissionTo($permission->name))?'checked':'' }}>
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
