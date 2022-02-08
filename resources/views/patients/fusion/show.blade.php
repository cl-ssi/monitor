@extends('layouts.app')

@section('title', 'Fusionar pacientes')

@section('content')

<h3>Fusiona exámenes de paciente de la izquierda (rojo) dentro de paciente de la derecha (verde)</h3>
<h4>Verificar datos demográficos, ya que se mantendrán sólo los datos de paciente verde</h4>

<div class="row">
    <div class="col-6 bg-danger">
        <a href="{{ route('patients.edit',$patient1) }}" class="btn btn-sm btn-primary"> Ver paciente </a><br>
        <b>ID:</b> {{ $patient1->id }} <br>
        <b>Nombre</b> {{ $patient1->fullName }}<br>
        <b>Run</b> {{ $patient1->run }} {{ $patient1->dv }}<br>
        <b>Otra identificación</b> {{ $patient1->other_identification }}<br>
        <b>Nacimiento</b> {{ $patient1->birthday }}<br>
        <b>Genero</b> {{ $patient1->gender }}<br>
        <b>Exámenes:</b>
        <ul>
        @foreach($patient1->suspectCases as $sc1)
            <li> <b>ID:</b> {{ $sc1->id }} <b>Fecha muestra: </b> {{ $sc1->sample_at }} <br> 
                <b>Fecha resultado</b> {{ $sc1->pcr_sars_cov_2_at }} <b>Resultado</b> {{ $sc1->pcr_sars_cov_2 }} 
            </li>
        @endforeach
        </ul>
    </div>
    <div class="col-6 bg-success">
        <a href="{{ route('patients.edit',$patient2) }}" class="btn btn-sm btn-primary"> Ver paciente </a><br>
        <b>ID:</b> {{ $patient2->id }} <br>
        <b>Nombre</b> {{ $patient2->fullName }}<br>
        <b>Run</b> {{ $patient2->run }} {{ $patient2->dv }}<br>
        <b>Otra identificación</b> {{ $patient2->other_identification }}<br>
        <b>Nacimiento</b> {{ $patient2->birthday }}<br>
        <b>Genero</b> {{ $patient2->gender }}<br>

        <b>Exámenes:</b>
        <ul>
        @foreach($patient2->suspectCases as $sc2)
            <li> <b>ID:</b> {{ $sc2->id }} <b>Fecha muestra: </b> {{ $sc2->sample_at }} <br> 
                <b>Fecha resultado</b> {{ $sc2->pcr_sars_cov_2_at }} <b>Resultado</b> {{ $sc2->pcr_sars_cov_2 }} 
            </li>
        @endforeach
        </ul>
    </div>
</div>

<br>
<br>

<form method="post" class="form-horizontal" action="{{ route('patients.fusion.do') }}">
    @csrf
    @method('POST')
    <div class="form-row">

        <div class="form-group col-3">
            <label for="">ID paciente 1</label>
            <input class="form-control" type="text" name="p1_id" value="{{ $patient1->id }}" required readonly>
        </div>

        <div class="form-group col-3">
            <label for="">ID paciente 2</label>
            <input class="form-control" type="text" name="p2_id" value="{{ $patient2->id }}" required readonly>
        </div>

        <div class="form-group col-4">
            <label for=""><b>Advertencia el paciente rojo será eliminado</b></label>
            <button type="submit" class="btn btn-primary form-control"><i class="fas fa-object-group"></i> FUSIONAR</button>
        </div>

        <div class="form-group col-1">
            <label for="">&nbsp;</label>
            <a href="{{ route('patients.fusion.create')}}" class="btn btn-outline-secondary form-control">Limpiar</a>
        </div>

    </div>

</form>

@endsection

@section('custom_js')

@endsection
