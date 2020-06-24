<h4 class="mt-4">Solicitudes</h4>

<table class="table table-sm table-bordered small">
    <thead>
        <tr class="text-center">
            <th>Fecha Solicitud</th>
            <th>Tipo de Solicitud</th>
            <th>Detalle</th>
            <th>Validez Hasta</th>
        </tr>
    </thead>
    <tbody>
        @foreach($patient->tracing->tracing_requests as $request)
        <tr class="text-right">
            <td>{{ $request->request_at->format('d-m-Y H:i:s') }}</td>
            <td>{{ $request->type->name }}</td>
            <td>{{ $request->details }}</td>
            <td>{{ ($request->validity_at)? $request->validity_at : '' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
