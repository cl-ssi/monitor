<h4 class="mt-3 mt-4">Historial de cambios seguimiento</h4>
<table class="table table-sm small text-muted mt-3">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Usuario</th>
            <th>Modificaciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($audits->sortByDesc('updated_at') as $audit)
        <tr>
            <td nowrap>{{ $audit->created_at }}</td>
            <td nowrap>{{ $audit->user->name }}</td>
            <td>
            @foreach($audit->getModified() as $attribute => $modified)
                @if(isset($modified['old']) OR isset($modified['new']))
                <strong>{{ $attribute }}</strong> :  {{ isset($modified['old']) ? $modified['old'] : '' }}  => {{ $modified['new'] }};
                @endif
            @endforeach
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
