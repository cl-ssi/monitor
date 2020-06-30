@extends('layouts.app')

@section('title', 'Seguimiento')

@section('content')

    <div class="row">
        <div class="col-12 col-sm-3">
            @isset($titulo)
                <h3 class="mb-3">{{$titulo}}</h3>
            @else
                <h3 class="mb-3">Seguimiento</h3>
            @endisset
        </div>
        <div class="col-12 col-sm-9" >
            <a type="button" class="btn btn-sm btn-outline-primary" href="{{ route('patients.tracings.completed') }}">
                Seguimientos finalizados
            </a>
            <!--a type="button" class="btn btn-sm btn-outline-primary" href="{{ route('patients.in_residence') }}">
                En residencia
            </a-->
        </div>
    </div>




<table class="table table-sm table-bordered small">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Comuna</th>
            <th>Establecimiento Muestra</th>
            <th>Inicio Cuarentena</th>
            <th>Fin de Cuarentena</th>
            <th>Notificacion</th>
            <th>Ultimo evento</th>
            <th>Funcionario</th>
        </tr>
    </thead>
    <tbody>
        @php $fecha = null; @endphp
        @foreach($patients as $key => $patient)
        @if($fecha != $patient->tracing->next_control_at->format('Y-m-d'))
        <tr>
            <td colspan="9" class="table-active">
                <h5>Siguiente Control: {{ $patient->tracing->next_control_at->format('Y-m-d') }}</h5>
            </td>
        </tr>
        @php $fecha = $patient->tracing->next_control_at->format('Y-m-d'); @endphp
        @endif
        <tr>
            <td>
                <a href="{{ route('patients.edit',$patient)}}" target="_blank">
                {{ $patient->id }}
                </a>
            </td>
            <td>{{ $patient->fullName }}</td>
            <td>{{ ($patient->demographic AND $patient->demographic->commune) ?
                    $patient->demographic->commune->name : '' }}</td>
            <td>{{ ($patient->tracing->establishment) ? $patient->tracing->establishment->alias : '' }}</td>
            <td nowrap>{{ $patient->tracing->quarantine_start_at->format('Y-m-d') }}</td>
            <td nowrap class="{{ ($patient->tracing->quarantine_end_at < Carbon\Carbon::now()->sub(1,'day')) ? 'text-danger' : ''}}">
                {{ $patient->tracing->quarantine_end_at->format('Y-m-d') }}
                ({{ $patient->tracing->quarantine_start_at->diffInDays(Carbon\Carbon::now()) }})
            </td>
            <td nowrap>{{ ($patient->tracing->notification_at)? $patient->tracing->notification_at->format('Y-m-d') : '' }}</td>
            <td nowrap>{{ ($patient->tracing->events->last()) ? $patient->tracing->events->last()->event_at->format('Y-m-d') : '' }}</td>
            <td>{{ ($patient->tracing->events->last()) ? $patient->tracing->events->last()->user->name : '' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>



@endsection

@section('custom_js')

@endsection
