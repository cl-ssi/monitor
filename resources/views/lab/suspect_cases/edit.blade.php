@extends('layouts.app')

@section('title', 'Editar sospecha')

@section('content')
<h3 class="mb-3">Editar sospecha</h3>

@include('patients.show',$suspectCase)

<hr>

<form method="POST" class="form-horizontal" action="{{ route('lab.suspect_cases.update', $suspectCase) }}">
    @csrf
    @method('PUT')

    <div class="form-row">

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_sample_at">Fecha Muestra</label>
            <input type="date" class="form-control" id="for_sample_at"
                name="sample_at" value="{{ (isset($suspectCase->sample_at))? $suspectCase->sample_at->format('Y-m-d'):'' }}">
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_origin">Origen</label>
            <select name="origin" id="for_origin" class="form-control">
                <option value=""></option>
                <option value="Hospital ETG" {{ ($suspectCase->origin == 'Hospital ETG')?'selected':'' }}>Hosptial ETG</option>
                <option value="Clínica Tarapacá" {{ ($suspectCase->origin == 'Clínica Tarapacá')?'selected':'' }}>Clínica Tarapacá</option>
                <option value="Clínica Iquique" {{ ($suspectCase->origin == 'Clínica Iquique')?'selected':'' }}>Clínica Iquique</option>
                <option value="Hector Reyno" {{ ($suspectCase->origin == 'Hector Reyno')?'selected':'' }}>Hector Reyno</option>
                <option value="CESFAM Guzmán" {{ ($suspectCase->origin == 'CESFAM Guzmán')?'selected':'' }}>CESFAM Guzmán</option>
                <option value="CESFAM Videla" {{ ($suspectCase->origin == 'CESFAM Videla')?'selected':'' }}>CESFAM Videla</option>
                <option value="Particular (SEREMI)" {{ ($suspectCase->origin == 'Particular (SEREMI)')?'selected':'' }}>Particular (SEREMI)</option>
            </select>
        </fieldset>



        <fieldset class="form-group col-8 col-md-4">
            <label for="for_status">Estado</label>
            <select name="status" id="for_status" class="form-control">
                <option value=""></option>
                <option value="Hospitalizado Básico" {{ ($suspectCase->status == 'Hospitalizado Básico')?'selected':'' }}>Hospitalizado Básico</option>
                <option value="Hospitalizado Crítico" {{ ($suspectCase->status == 'Hospitalizado Crítico')?'selected':'' }}>Hospitalizado Crítico</option>
                <option value="Alta" {{ ($suspectCase->status == 'Alta')?'selected':'' }}>Alta</option>
                <option value="Fallecido" {{ ($suspectCase->status == 'Fallecido')?'selected':'' }}>Fallecido</option>
                <option value="Ambulatorio" {{ ($suspectCase->status == 'Ambulatorio')?'selected':'' }}>Ambulatorio (domiciliario)</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-4 col-md-2">
            <label for="for_age">Edad</label>
            <input type="number" class="form-control" id="for_age" name="age"
                value="{{ $suspectCase->age }}">
        </fieldset>

    </div>

    <div class="form-row">

        <fieldset class="form-group col-6 col-md-2 alert-warning">
            <label for="for_result_ifd_at">Fecha Resultado IFD</label>
            <input type="date" class="form-control" id="for_result_ifd_at"
                name="result_ifd_at" value="{{( isset($suspectCase->result_ifd_at))?  $suspectCase->result_ifd_at->format('Y-m-d'):'' }}">
        </fieldset>

        <fieldset class="form-group col-6 col-md-2 alert-warning">
            <label for="for_result_ifd">Resultado IFD</label>
            <select name="result_ifd" id="for_result_ifd" class="form-control">
                <option></option>
                <option value="Negativo"
                    {{ ($suspectCase->result_ifd == 'Negativo')?'selected':'' }}>
                    Negativo
                </option>
                <option value="Adenovirus"
                    {{ ($suspectCase->result_ifd == 'Adenovirus')?'selected':'' }}>
                    Adenovirus
                </option>
                <option value="Influenza A"
                    {{ ($suspectCase->result_ifd == 'Influenza A')?'selected':'' }}>
                    Influenza A
                </option>
                <option value="Influenza B"
                    {{ ($suspectCase->result_ifd == 'Influenza B')?'selected':'' }}>
                    Influenza B
                </option>
                <option value="Metapneumovirus"
                    {{ ($suspectCase->result_ifd == 'Metapneumovirus')?'selected':'' }}>
                    Metapneumovirus
                </option>
                <option value="Parainfluenza 1"
                    {{ ($suspectCase->result_ifd == 'Parainfluenza 1')?'selected':'' }}>
                    Parainfluenza 1
                </option>
                <option value="Parainfluenza 2"
                    {{ ($suspectCase->result_ifd == 'Parainfluenza 2')?'selected':'' }}>
                    Parainfluenza 2
                </option>
                <option value="Parainfluenza 3"
                    {{ ($suspectCase->result_ifd == 'Parainfluenza 3')?'selected':'' }}>
                    Parainfluenza 3
                </option>
                <option value="VRS"
                    {{ ($suspectCase->result_ifd == 'VRS')?'selected':'' }}>
                    VRS
                </option>
                <option value="No procesado"
                    {{ ($suspectCase->result_ifd == 'No procesado')?'selected':'' }}>
                    No procesado
                </option>
                </select>
        </fieldset>


        <fieldset class="form-group col-6 col-md-2 alert-warning">
            <label for="for_subtype">Subtipo</label>
            <select name="subtype" id="for_subtype" class="form-control">
                <option value=""></option>
                <option value="H1N1" {{ ($suspectCase->subtype == "H1N1")?'selected':'' }}>H1N1</option>
                <option value="H3N2" {{ ($suspectCase->subtype == "H3N2")?'selected':'' }}>H3N2</option>
                <option value="INF B" {{ ($suspectCase->subtype == "INF B")?'selected':'' }}>INF B</option>
            </select>
        </fieldset>

    </div>

    <div class="form-row">

        <fieldset class="form-group col-6 col-md-2 alert-danger">
            <label for="for_sent_isp_at">Fecha envío a ISP</label>
            <input type="date" class="form-control" id="for_sent_isp_at"
                name="sent_isp_at" value="{{ isset($suspectCase->sent_isp_at)? $suspectCase->sent_isp_at->format('Y-m-d'):'' }}">
        </fieldset>

        <fieldset class="form-group col-6 col-md-2 alert-danger">
            <label for="for_pscr_sars_cov_2_at">Fecha Resultado PCR</label>
            <input type="date" class="form-control" id="for_pscr_sars_cov_2_at"
                name="pscr_sars_cov_2_at" value="{{ isset($suspectCase->pscr_sars_cov_2_at)? $suspectCase->pscr_sars_cov_2_at->format('Y-m-d'):'' }}">
        </fieldset>

        <fieldset class="form-group col-6 col-md-2 alert-danger">
            <label for="for_pscr_sars_cov_2">PCR SARS-Cov2</label>
            <select name="pscr_sars_cov_2" id="for_pscr_sars_cov_2"
                class="form-control">
                <option value="pending" {{ ($suspectCase->pscr_sars_cov_2 == 'pending')?'selected':'' }}>Pendiente</option>
                <option value="negative" {{ ($suspectCase->pscr_sars_cov_2 == 'negative')?'selected':'' }}>Negativo</option>
                <option value="positive" {{ ($suspectCase->pscr_sars_cov_2 == 'positive')?'selected':'' }}>Positivo</option>
            </select>
        </fieldset>

    </div>

    <div class="form-row">

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_paho_flu">PAHO FLU</label>
            <input type="number" class="form-control" name="paho_flu" id="for_paho_flu"
                value="{{ $suspectCase->paho_flu }}">
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_epivigila">Epivigila</label>
            <input type="number" class="form-control" id="for_epivigila"
                name="epivigila" value="{{ $suspectCase->epivigila }}">
        </fieldset>

        <fieldset class="form-group col-12 col-md-8">
            <label for="for_observation">Observación</label>
            <input type="text" class="form-control" name="observation"
                id="for_observation" value="{{ $suspectCase->observation }}">
        </fieldset>

    </div>


    <hr>
    <div class="form-row">


    <button type="submit" class="btn btn-primary">Guardar</button>

    <a class="btn btn-outline-secondary" href="{{ route('lab.suspect_cases.index') }}">
        Cancelar
    </a>
</form>

@can('Admin')
<table class="table table-sm small text-muted mt-3">
    <thead>
        <tr>
            <th colspan="4">Historial de cambios</th>
        </tr>
        <tr>
            <th>Modelo</th>
            <th>Fecha</th>
            <th>Usuario</th>
            <th>Modificaciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($suspectCase->logs as $log)
        <tr>
            <td>{{ $log->model_type }}</td>
            <td>{{ $log->created_at }}</td>
            <td>{{ $log->user->name }}</td>
            <td>
                @foreach($log->diferencesArray as $key => $diference)
                    {{ $key }} => {{ $diference}} <br>
                @endforeach
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endcan

@endsection

@section('custom_js')

@endsection
