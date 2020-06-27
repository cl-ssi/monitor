<h4 class="mt-4">Solicitudes</h4>

<table class="table table-sm table-bordered small">
    <thead>
        <tr class="text-center">
            <th>Fecha Solicitud</th>
            <th>Tipo de Solicitud</th>
            <th>Detalle</th>
            <th>Validez Hasta</th>
            <th>Funcionario Solicitud</th>
            <th>Fecha Respuesta</th>
            <th>Funcionario Respuesta</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($patient->tracing->tracing_requests as $request)
        <tr class="text-right">
            <td>{{ $request->request_at->format('d-m-Y H:i:s') }}</td>
            <td>{{ $request->type->name }}</td>
            <td>{{ $request->details }}</td>
            <td>{{ ($request->validity_at)? \Carbon\Carbon::parse($request->validity_at)->format('d-m-Y') : '' }}</td>
            <td>{{ $request->user->name }}</td>
            <td>{{ ($request->request_complete_at) ? $request->request_complete_at->format('d-m-Y H:i:s') : '' }}</td>
            <td>{{ ($request->user_complete_request_id) ? $request->request_complete_user->name : '' }}</td>
            <td>
            @if($request->request_complete_at)
              @if($request->rejection == 1)
                  Solicitud Rechazada
              @else
                  Solicitud Aceptada
              @endif
            @else
              Solicitud Pendiente
            @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
