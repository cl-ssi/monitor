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
            <th>Eliminar</th>
        </tr>
    </thead>
    <tbody>
        
        @foreach($residenceUsers as $residenceUser)
        <tr>
        
            <td>{{ $residenceUser->user->name }}</td>
            <td>{{ $residenceUser->residence->name }}</td>
            <td>
                <form method="POST" class="form-horizontal" action="{{ route('sanitary_residences.users.destroy',$residenceUser) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger float-left" onclick="return confirm('¿Está seguro que desea eliminar los permisos del usuario: {{ $residenceUser->user->name }} para la residencia sanitaria: {{ $residenceUser->residence->name }}? ' )"><i class="fas fa-trash-alt"></i></button>
                </form>                
            </td>
        </tr>
        @endforeach
        
    </tbody>
</table>

@endsection

@section('custom_js')

@endsection