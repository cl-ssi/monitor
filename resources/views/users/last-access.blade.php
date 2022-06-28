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
                <th class="text-center">Fecha y hora</th>
                <th class="text-center">IP</th>
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
            @endforeach
        </tbody>
    </table>

    {{ $logSessions->links() }}
</div>

@endsection