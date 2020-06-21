@extends('layouts.app')

@section('title', 'Home Residencias')

@section('content')

@include('sanitary_residences.nav')

<h3 class="mb-3">Listado de Encuestas Aprobadas</h3>
<table class="table table-sm table-bordered text-center align-middle">
  <thead>
    <tr>
      <th>Nombre de Encuestado</th>
      <th>Fecha de Encuesta</th>
      <th>Fecha Digitación en Sistema de Encuesta</th>
      <th>Encuesta Digitada en Sistema por</th>
      <th>¿Es Posible Aislar al Paciente?</th>
      <th>¿Califica Residencia?</th>
      <th>Resultado</th>
      <th>Resultado Final</th>
      <th>Hotel - Habitación</th>
      <th>Ver encuesta</th>
    </tr>
  </thead>
  <tbody>
    @foreach($admissions as $admission)
    <tr>
      <td class="text-center align-middle">{{ $admission->patient->full_name }}</td>
      <td class="text-center align-middle">{{ $admission->created_at }}</td>
      <td class="text-center align-middle">{{ $admission->updated_at }}</td>
      <td class="text-center align-middle">{{ $admission->user->name }}</td>
      <td class="text-center align-middle">{{ $admission->isolate_text }}</td>
      <td class="text-center align-middle">{{ $admission->residency_text }}</td>
      <td class="text-center align-middle" nowrap>{!! $admission->result !!}</td>
      <td class="text-center align-middle">{{ $admission->status }}</td>      
      <td class="text-center align-middle">{{ ($admission->patient->bookings->last())?($admission->patient->bookings->last()->room->residence->name) .'- Habitación '. ($admission->patient->bookings->last()->room->number): 'No se le ha asignado Habitación Todavía' }}</td>      
      <td class="text-center align-middle"><a class="btn btn-success btn-sm" href="{{ route('sanitary_residences.admission.show', $admission) }}">
          <i class="fas fa-poll-h"></i> Revisar Encuesta
        </a></td>
    </tr>
    @endforeach
</table>

@endsection




@section('custom_js')

@endsection