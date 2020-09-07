@extends('layouts.app')

@section('title', 'Listado de Personas Aprobadas para Residencia Sanitaria')

@section('content')

@include('sanitary_residences.nav')

<h3 class="mb-3">Listado de Personas Aprobadas para Residencia Sanitaria</h3>



<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Rut/ID</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Fono</th>
            <th>Fecha Encuesta</th>
            <th>Observaciones</th>
            <th>Aprobar Ingreso</th>
            <th>Rechazar Ingreso (Elimina Encuesta)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($admissions as $admission)
        <tr>
            <td>{{$admission->patient->identifier}}</td>
            <td>{{$admission->patient->fullname}}</td> 
            <td>{{($admission->patient->demographic)?$admission->patient->demographic->fulladdress:''}}</td>            
            <td>{{ ($admission->patient->demographic)?$admission->patient->demographic->telephone:'' }}</td>
            <td>{{$admission->created_at}}</td>
            <td>{{$admission->observations}}</td>
            <td>
                
                <a href="{{ route('sanitary_residences.bookings.createfrompatient', $admission->patient)}}?paciente={{$admission->patient->id}}&morbid_history={{$admission->morbid_history}}&observations={{$admission->observations}}&symptoms_epivigila={{$admission->symptoms_epivigila}}" class="btn btn-secondary float-left"><i class="fa fa-bed"></i></a>
            </td>
            <td>
            <form method="POST" class="form-horizontal" action="{{ route('sanitary_residences.admission.destroy', $admission) }}">
            @csrf
            @method('DELETE')            
            <button type="submit" class="btn btn-danger float-left" onclick="return confirm('Recuerde que esta acción debe realizarla solamente en casos muy excepcionales (Paciente no llego a la Residencia Sanitaria). Al hacer esto se ELIMINARA la encuesta realizada por el calificador' )"><i class="fa fa-ban"></i></button>
            </form> 
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')

@endsection