<h4 class="mt-3">Historial de cambios</h4>
<div class="table-responsive-md">
<table class="table table-sm small text-muted mt-3">
    <thead>
        <tr>
            <th>IP</th>
            <th>URL</th>
            <th>Modelo</th>
            <th>Fecha</th>
            <th>Usuario</th>
            <th>Evento</th>
            <th>Modificaciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($audits->sortByDesc('updated_at') as $audit)
        <tr>
            <td>{{ $audit->ip_address }}</td>
            <td>{{ $audit->url }}</td>
            <td>{{ $audit->auditable_type }}</td>
            <td>{{ $audit->created_at }}</td>
            <td>{{ ($audit->user) ? $audit->user->name : 'Sistema' }}</td>
            <td>{{ $audit->event }}</td>
            <td>
            @foreach($audit->getModified() as $attribute => $modified)
                <strong>{{ $attribute?? '' }}</strong> :  {{ isset($modified['old']) ? $modified['old'] : '' }}  => {{ $modified['new'] ?? '' }} <br>
            @endforeach
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
