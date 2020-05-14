@extends('layouts.app')

@section('title', 'Usuarios de las residencias')

@section('content')

@include('sanitary_residences.nav')

<h3 class="mb-3">Usuarios de las residencias</h3>

<form method="POST" class="form-horizontal" action="{{ route('sanitary_residences.users.store') }}">
    @csrf
    @method('POST')

    <div class="form-row">
        <fieldset class="form-group col">
            <label for="for_residence_id">Residencias</label>
            <select name="residence_id" id="for_residence_id" class="form-control">
                @foreach($residences as $res)
                <option value="{{ $res->id }}">{{ $res->name }}</option>
                @endforeach
            </select>
        </fieldset>

        <fieldset class="form-group col">
            <label for="for_user_id">Usuarios</label>
            <select name="user_id" id="for_user_id" class="form-control">
                @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </fieldset>

    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>

</form>

<hr>

<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Usuario</th>
            <th>Residencia</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($users_with_residences as $user)
        @foreach($user->residences as $residence)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $residence->name }}</td>
            <td></td>
        </tr>
        @endforeach
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')

@endsection
