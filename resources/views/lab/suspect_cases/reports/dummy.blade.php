@extends('layouts.app')

@section('title', 'Pacientes que requieren algún tipo de apoyo')

@section('content')
<h3 class="mb-3">Personas que requieren algún tipo de apoyo</h3>

<ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Licencia Médica</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Canasta de Ayuda</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Ayúda Psicológica</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="contact" aria-selected="false">Agua Potable</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Medicamentos</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="contact" aria-selected="false">Asistencia Médica</a>
    </li>
</ul>

<div class="tab-content mb-4" id="myTabContent">
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <h4>Canasta de Ayuda</h4>

        <table class="table table-sm table-bordered small">
            <thead>
                <tr class="text-center">
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Comuna de Residencia</th>
                    <th>Establecimiento de Seguimiento</th>
                    <th>Dirección</th>
                    <th>Fecha de resolución</th>
                    <th>Resolver</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patients->where('tracing.help_basket', 1) as $key => $patient)
                <tr>
                    <td class="text-right">{{ ++$key }}</td>
                    <td nowrap class="text-right">
                        <a href="{{ route('patients.edit',$patient)}}">
                        {{ $patient->fullName }}
                        </a>
                    </td>
                    <td class="text-right">{{ ($patient->demographic AND $patient->demographic->commune) ? $patient->demographic->commune->name : ''}}</td>
                    <td class="text-right">{{ ($patient->tracing) ? $patient->tracing->establishment->alias : '' }}</td>
                    <td>{{ $patient->demographic->address }} {{ $patient->demographic->number }}</td>
                    <td></td>
                    <td>
                        <button type="submit" class="btn btn-sm btn-primary">Resolver</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <h4>Licencia Médica</h4>

        <table class="table table-sm table-bordered small">
            <thead>
                <tr class="text-center">
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Comuna de Residencia</th>
                    <th>Establecimiento de Seguimiento</th>
                    <th>Dirección</th>
                    <th>Fecha de resolución</th>
                    <th>Resolver</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patients->where('tracing.requires_licence', 1) as $key => $patient)
                <tr>
                    <td class="text-right">{{ ++$key }}</td>
                    <td nowrap class="text-right">
                        <a href="{{ route('patients.edit',$patient)}}">
                        {{ $patient->fullName }}
                        </a>
                    </td>
                    <td class="text-right">{{ ($patient->demographic AND $patient->demographic->commune) ? $patient->demographic->commune->name : ''}}</td>
                    <td class="text-right">{{ ($patient->tracing) ? $patient->tracing->establishment->alias : '' }}</td>
                    <td>{{ $patient->demographic->address }} {{ $patient->demographic->number }}</td>
                    <td></td>
                    <td>
                        <button type="submit" class="btn btn-sm btn-primary">Resolver</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
        <h4>Ayuda Piscológica</h4>

        <table class="table table-sm table-bordered small">
            <thead>
                <tr class="text-center">
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Comuna de Residencia</th>
                    <th>Establecimiento de Seguimiento</th>
                    <th>Dirección</th>
                    <th>Fecha de resolución</th>
                    <th>Resolver</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patients->where('tracing.psychological_intervention', 1) as $key => $patient)
                <tr>
                    <td class="text-right">{{ ++$key }}</td>
                    <td nowrap class="text-right">
                        <a href="{{ route('patients.edit',$patient)}}">
                        {{ $patient->fullName }}
                        </a>
                    </td>
                    <td class="text-right">{{ ($patient->demographic AND $patient->demographic->commune) ? $patient->demographic->commune->name : ''}}</td>
                    <td class="text-right">{{ ($patient->tracing) ? $patient->tracing->establishment->alias : '' }}</td>
                    <td>{{ $patient->demographic->address }} {{ $patient->demographic->number }}</td>
                    <td></td>
                    <td>
                        <button type="submit" class="btn btn-sm btn-primary">Resolver</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

<h4>Georeferencia</h4>
<img src="{{ asset('images/needs.png')}}" alt="" width="900">
@endsection

@section('custom_js')

@endsection
