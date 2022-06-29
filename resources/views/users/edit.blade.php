@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')

@include('parameters.nav')

<h3 class="mb-3">Editar Usuario</h3>

<form method="POST" class="form-horizontal" action="{{ route('users.update',$user) }}">
    @csrf
    @method('PUT')
    <div class="card mb-3">
        <div class="card-body">

            <div class="form-row">

                <fieldset class="form-group col-8 col-md-2">
                    <label for="for_run">Run</label>
                    <input type="number" class="form-control" name="run" id="for_run"
                        value="{{ $user->run }}" required>
                </fieldset>

                <fieldset class="form-group col-4 col-md-1">
                    <label for="for_dv">Dv</label>
                    <input type="text" class="form-control" name="dv" id="for_dv"
                        value="{{ $user->dv }}" required>
                </fieldset>

                <fieldset class="form-group col-12 col-md-4">
                    <label for="for_name">Nombre y Apellido</label>
                    <input type="text" class="form-control" name="name" id="for_name"
                        value="{{ $user->name }}" required>
                </fieldset>

                <fieldset class="form-group col-12 col-md-3">
                    <label for="for_email">Email</label>
                    <input type="email" class="form-control" name="email" id="for_email"
                        value="{{ $user->email }}" required style="text-transform: lowercase;">
                </fieldset>

                <fieldset class="form-group col-6 col-md-2">
                    <label for="for_telephone">Telefono</label>
                    <input type="text" class="form-control" name="telephone" id="for_telephone" placeholder="ej:+56912345678"
                    value="{{ $user->telephone }}">
                </fieldset>

            </div>

            <div class="form-row">
                
                <fieldset class="form-group col-12 col-md-5">
                    <label for="for_my_establishment_id">Establecimiento al que pertenece*</label>
                    <select name="my_establishment_id" id="my_establishment_id" class="form-control">
                        <option value=""></option>
                        @foreach($establishments as $establishment)
                            <option value="{{ $establishment->id }}" {{ ($user->establishment_id == $establishment->id) ? 'selected' : '' }}>{{ $establishment->alias }}</option>
                        @endforeach

                    </select>
                </fieldset>

                <fieldset class="form-group col-12 col-md-4">
                    <label for="for_function">Función que cumple</label>
                    <input type="text" class="form-control" name="function" id="for_function"
                    value="{{ $user->function }}">
                </fieldset>

                <fieldset class="form-group col-12 col-md-3">
                    <label for="for_laboratory_id">Laboratorio asociado</label>
                    <select name="laboratory_id" id="for_laboratory_id" class="form-control">
                        <option value=""></option>
                        @foreach($laboratories as $lab)
                        <option value="{{ $lab->id }}" {{ ($user->laboratory_id == $lab->id)?'selected':'' }}>{{ $lab->alias }}</option>
                        @endforeach
                    </select>
                </fieldset>



            </div>

            <div class="form-row">

                <fieldset class="form-group col-12 col-md-12">
                    <label for="for_establishment_id">Acceso a astablecimientos *</label>
                    <select name="establishment_id[]" id="for_establishment_id" class="form-control selectpicker" data-live-search="true" multiple="" data-size="10" title="Seleccione..." multiple data-actions-box="true" required>

                            @foreach($establishments as $establishment)
                                <option value="{{ $establishment->id }}" @if(in_array($establishment->id, $establishment_selected)) selected="selected" @endif>{{ $establishment->alias }}</option>
                            @endforeach

                    </select>
                </fieldset>

            </div>

            <div class="form-row">
                <fieldset class="form-group col-12 col-md-6">
                    <a class="btn btn-primary btn-sm" href="{{ route('users.password.restore', $user) }}">
                        <i class="fas fa-plus"></i> Generar Nueva Contraseña
                    </a>
                </fieldset>
                <fieldset class="form-group col-12 col-md-6 text-right">
                    <button
                        class="btn btn-warning btn-sm"
                        type="submit"
                        onclick="return confirm('¿Está seguro que desea @if($user->active) Desactivar @else Activar @endif al usuario {{ $user->name }}?' )"
                        form="update_active"
                    >
                        @if($user->active) <i class="fa fa-lock"></i> @else <i class="fa fa-lock-open"></i> @endif
                        @if($user->active) Desactivar @else Activar @endif
                        Usuario
                    </button>

                    <button
                        class="btn btn-danger btn-sm"
                        type="submit"
                        onclick="return confirm('¿Está seguro que desea eliminar al usuario {{ $user->name }}?' )"
                        form="delete_form"
                    >
                        <i class="fas fa-user-slash"></i> Eliminar Usuario
                    </button>
                </fieldset>
            </div>

        </div>
    </div>

    <hr>

    <h3>Sesiones</h3>
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th>Usuario</th>
                    <th class="text-center">Fecha y hora</th>
                    <th class="text-center">IP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($user->lastSessions as $logSession)
                <tr>
                    <td class="text-center">
                        {{ $logSession->id }}
                    </td>
                    <td>
                        {{ $logSession->user->name }}
                    </td>
                    <td class="text-center">
                        {{ $logSession->created_at }}
                    </td>
                    <td class="text-center">
                        <span class="text-monospace">
                            {{ $logSession->ip }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="text-center" colspan="4">
                        <em>No hay registros</em>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <hr>

    <div class="form-row">
        <div class="col">

            @php
            $renderedCategories = array();
            @endphp

            <h3>Permisos</h3>
            @foreach($permissions as $permission)

            @if(!in_array($permission->category, $renderedCategories))
            <h4>{{ $permission->category }}</h4>
            @php
            $renderedCategories[] = $permission->category;
            @endphp
            @endif

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="permissions[]" onclick="if('{{ $permission->name }}' === 'Admin'|| '{{ $permission->name }}' === 'Developer' && this.checked) return confirm('¿Estás seguro de que quieres darle el rol {{$permission->name}} a {{$user->name}}?\n¡Este es un permiso peligroso!')"
                    value="{{ $permission->name }}" {{ ($user->hasPermissionTo($permission->name))?'checked':'' }} >
                <label class="form-check-label">
                    {{ $permission->name }}
                </label>
                <p class="text-muted">{{ $permission->description }}</p>
            </div>
            @endforeach
        </div>
        <!-- <div class="col">
            <h3>Establecimientos:</h3>
            @foreach($establishments_user as $establishment_user)
              {{ $establishments_user }}
            @endforeach
        </div> -->
    </div>
    <button type="submit" class="btn btn-primary mt-3">Guardar</button>


</form>

<form method="POST" id="update_active" action="{{ route('users.update-active', $user) }}">
    @csrf
</form>

<form method="POST" id="delete_form" action="{{route('users.destroy', $user)}}">
    @csrf
    @method('DELETE')
</form>


@endsection

@section('custom_js')

<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-select.min.css') }}">

<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('js/defaults-es_CL.min.js') }}"></script>

@endsection
