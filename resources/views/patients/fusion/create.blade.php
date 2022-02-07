@extends('layouts.app')

@section('title', 'Fusionar pacientes')

@section('content')

<h3>Fusionar examenes de paciente 1 dentro de paciente 2</h3>

<form method="post" class="form-horizontal" action="{{ route('patients.fusion.show') }}">
    @csrf
    @method('POST')
    <div class="form-row">

        <div class="form-group col-3">
            <label for="">ID paciente 1</label>
            <input class="form-control" type="text" name="p1_id" autocomplete="off" required>
        </div>

        <div class="form-group col-3">
            <label for="">ID paciente 2</label>
            <input class="form-control" type="text" name="p2_id" autocomplete="off" required>
        </div>

        <div class="form-group col-3">
            <label for="">&nbsp;</label>
            <button type="submit" class="btn btn-primary form-control"><i class="fas fa-search"></i> Buscar</button>
        </div>

    </div>

</form>

@endsection

@section('custom_js')

@endsection
