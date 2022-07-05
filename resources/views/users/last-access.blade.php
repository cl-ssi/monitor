@extends('layouts.app')

@section('title', 'Últimos Accesos')

@section('content')

@include('parameters.nav')

<h3 class="mb-3">Últimos Accesos</h3>

<div class="table-responsive">
    <table class="table table-sm table-striped table-bordered">
        <thead>
            <tr>
                <th class="text-center">ID</th>
                <th>Usuario</th>
                <th>Establecimiento</th>
                <th>Función</th>
                <th class="text-center small">Fecha y hora</th>
                <th class="text-center small">IP</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logSessions as $logSession)
            <tr>
                <td class="text-center">
                    {{ $logSession->id }}
                </td>
                <td>
                    <a href="{{ route('users.edit', $logSession->user) }}">
                        {{ $logSession->user->name }}
                    </a>
                    {{ $logSession->user->active == false ? '(x)':'    ' }}
                </td>
                <td>
                    {{ optional($logSession->user->establishment)->alias }}
                </td>
                <td>
                    {{ $logSession->user->function }}
                </td>
                <td class="text-center small" nowrap>
                    {{ $logSession->created_at }}
                </td>
                <td>
                    {{ $logSession->app_name }}
                </td>
                <td class="text-center small" nowrap>
                    <span class="text-monospace">
                        {{ $logSession->ip }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
{{ $logSessions->links() }}

@endsection
