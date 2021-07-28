@extends('layouts.app')

@section('title', 'Listado de Pacientes Candidatos a Secuenciación')

@section('content')
<ul class="nav nav-tabs mb-3 d-print-none">

    <li class="nav-item">
        <a class="nav-link" href="{{ route('sequencing.index') }}">
            <i class="fas fa-ban"></i> Sin Datos
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('sequencing.indexsend') }}">
            <i class="fas fa-plane-departure"></i> Enviados a Seq.
        </a>
    </li>
</ul>
@if(isset($send))
<h3 class="mb-3"><i class="fas fa-plane-departure"></i> Listado de Pacientes Candidato Enviado a Secuenciación</h3>
@else
<h3 class="mb-3"><i class="fas fa-user-injured"></i> Listado de Pacientes Candidato a Secuenciación Sin Datos</h3>
@endif






<div class="table-responsive">
    <table class="table table-sm table-bordered table-striped small">
        <thead>
            <tr class="text-center">
                @if(!isset($send))
                <th>Añadir Datos Secuenciación</th>
                @endif
                <th>Caso Sospecha</th>
                <th>CT</th>
                <th>Run o (ID)</th>
                <th>Nombre</th>
                <th>Genero</th>
                <th>Fecha Nac.</th>
                <th>Comuna</th>
                <th>Dirección</th>
                <th>Teléfono</th>                
                @if(isset($send))
                <th>Fecha de envío a secuenciación</th>
                @else
                <th>Enviar a secuenciación</th>
                @endif
                @if(isset($send))
                <th>Criterio</th>
                <th>Vacunación(fecha ult.)</th>
                <th>Hospitalización</th>
                <th>Diagnostico</th>


                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($sequencingcriterias as $sequencingcriteria)
            <tr>
                @if(!isset($send))
                <td>
                
                    <a href="{{ route('sequencing.edit', $sequencingcriteria) }}">
                        Agregar
                    </a>                
                </td>
                @endif
                <td>{{$sequencingcriteria->suspect_case_id ?? ''}}</td>
                <td>{{$sequencingcriteria->suspectCase->ct ?? ''}}</td>
                <td>{{$sequencingcriteria->suspectCase->patient->identifier ?? ''}}</td>
                <td>{{$sequencingcriteria->suspectCase->patient->fullname ?? ''}}</td>
                <td>{{$sequencingcriteria->suspectCase->patient->genderesp ?? ''}}</td>
                <td>{{$sequencingcriteria->suspectCase->patient->birthday? $sequencingcriteria->suspectCase->patient->birthday->format('d-m-Y'): ''}}</td>
                <td>{{$sequencingcriteria->suspectCase->patient->demographic->commune->name ?? '' }}</td>
                <td>{{$sequencingcriteria->suspectCase->patient->demographic->fulladdress ?? '' }}</td>
                <td>{{$sequencingcriteria->suspectCase->patient->demographic->telephone ?? '' }}</td>                
                <td>
                @if(isset($send))
                {{$sequencingcriteria->send_at?? ''}}
                @else

                    <form method="POST" class="form-horizontal" action="{{ route('sequencing.send', $sequencingcriteria) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit">
                            <i class="fas fa-share-square"></i> Enviar
                        </button>
                    </form>
                @endif
                </td>
                @if(isset($send))
                <td>{{$sequencingcriteria->critery ?? '' }}</td>
                <td>{{$sequencingcriteria->vaccination ?? '' }} (<small>{{$sequencingcriteria->last_dose_at ?? '' }}</small>)</td>
                <td>{{$sequencingcriteria->hospitalization_status ?? '' }}</td>
                <td>{{$sequencingcriteria->diagnosis ?? '' }}</td>
                

                @endif
            </tr>
            @endforeach

        </tbody>

</div>

@endsection

@section('custom_js')

@endsection