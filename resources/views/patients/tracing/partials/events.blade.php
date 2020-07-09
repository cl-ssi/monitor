<h4 class="mt-4">Seguimiento epidemiológico</h4>
<div class="table-responsive-sm">
<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Funcionario</th>
            <th>Tipo de evento</th>
            <th>Detalle</th>
            <th>Síntomas</th>
        </tr>
    </thead>
    <tbody>
        @foreach($patient->tracing->events as $event)
        <tr>
            <td>{{ $event->event_at }}</td>
            <td>{{ $event->user->name }}</td>
            <td>{{ $event->type->name }}</td>
            <td>{{ $event->details }}</td>
            <td>{{ $event->symptoms }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
