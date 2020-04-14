<h3 class="mb-3">Listado de Signos Vitales</h3>

<table class="table table-sm table-bordered table-responsive">
    <thead>
        <tr>
            <th>Fecha y hora</th>
            <th>Chequeado por</th>
            <th>Temperatura</th>
            <th>Frec. Cardiaca</th>
            <th>Presión Arterial</th>
            <th>Frec. Respiratoria</th>
            <th>SAT 02</th>
            <th>HGT</th>
            <th>Escala Dolor</th>
            <th>Observaciones</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($booking->vitalSigns->reverse() as $vitalsign)
        <tr>
            <td nowrap>{{ $vitalsign->created_at->format('d-m-Y H:i') }}</td>
            <td nowrap>{{ $vitalsign->user->name }}</td>
            <td>{{ $vitalsign->temperature }}</td>
            <td>{{ $vitalsign->heart_rate }}</td>
            <td>{{ $vitalsign->blood_pressure }}</td>
            <td>{{ $vitalsign->respiratory_rate }}</td>
            <td>{{ $vitalsign->oxygen_saturation }}</td>
            <td>{{ $vitalsign->hgt }}</td>
            <td>{{ $vitalsign->pain_scale }}</td>
            <td>{{ $vitalsign->observations }}</td>

            <td><button type="submit" class="btn btn-outline-secondary btn-sm" id="btn_{{$vitalsign->id}}">
    							<i class="fas fa-edit"></i>
    						</button>
            </td>

        </tr>
        @endforeach

    </tbody>
</table>
