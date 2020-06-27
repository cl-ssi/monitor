@extends('layouts.app')

@section('title', 'Listado de Residencias')

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
                <a href="{{ route('sanitary_residences.bookings.create')}}?paciente={{$admission->patient->id}}&morbid_history={{$admission->morbid_history}}&observations={{$admission->observations}}&symptoms_epivigila={{$admission->symptoms_epivigila}}" class="btn btn-secondary float-left"><i class="fa fa-bed"></i></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')

@endsection