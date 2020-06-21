<h3 class="mb-3">Listado de Signos Vitales</h3>

<table class="table table-sm table-bordered table-responsive text-center align-middle">
    <thead>
        <tr>            
            <th>Fecha y hora Toma de Muestra</th>
            <th>Fecha y hora Digitación</th>
            <th>Temp.</th>
            <th>Frec. Card.</th>
            <th>P. Arterial</th>
            <th>Frec. Resp.</th>
            <th>SAT 02</th>
            <th>HGT</th>
            <th>Escala Dolor</th>
            <th>Observaciones</th>
            <th>Funcionario</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($booking->vitalSigns->reverse() as $vitalsign)
        <tr>        
            <td nowrap>{{ $vitalsign->created_at->format('d-m-Y H:i') }}</td>            
            @if($vitalsign->created_at->diffInHours($vitalsign->updated_at) > 2)
            <td nowrap class="table-danger">{{ $vitalsign->updated_at->format('d-m-Y H:i') }}</td>
            @else
            <td nowrap>{{ $vitalsign->updated_at->format('d-m-Y H:i') }}</td>
            @endif
            
            <td class="text-center">{{ $vitalsign->temperature }}</td>
            <td class="text-center">{{ $vitalsign->heart_rate }}</td>
            <td class="text-center">{{ $vitalsign->blood_pressure }}</td>
            <td class="text-center">{{ $vitalsign->respiratory_rate }}</td>
            <td class="text-center">{{ $vitalsign->oxygen_saturation }}</td>
            <td class="text-center">{{ $vitalsign->hgt }}</td>
            <td class="text-center">{{ $vitalsign->pain_scale }}</td>
            <td>{{ $vitalsign->observations }}</td>
            <td nowrap>{{ $vitalsign->user->name }}</td>
            <td>
                @if($vitalsign->updated_at->diff(now())->days < 1 and  $vitalsign->user->id == Auth::id())
                <button type="submit" class="btn btn-outline-secondary btn-sm" id="btn_{{$vitalsign->id}}">
    				<i class="fas fa-edit"></i>
    			</button>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
