@extends('layouts.app')

@section('title', 'Editar sospecha')

@section('content')
<h3 class="mb-3">Editar sospecha {{ $suspectCase->id }}</h3>

@include('patients.show',$suspectCase)

<hr>

<form method="POST" class="form-horizontal" action="{{ route('lab.suspect_cases.update', $suspectCase) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-row">

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_sample_at">Fecha Muestra</label>
            <input type="date" class="form-control" id="for_sample_at"
                name="sample_at" value="{{ (isset($suspectCase->sample_at))? $suspectCase->sample_at->format('Y-m-d'):'' }}">
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_sample_type">Tipo de Muestra</label>
            <select name="sample_type" id="for_sample_type" class="form-control">
                <option value=""></option>
                <option value="TÓRULAS NASOFARÍNGEAS" {{ ($suspectCase->sample_type == 'TÓRULAS NASOFARÍNGEAS')?'selected':'' }}>TORULAS NASOFARINGEAS</option>
                <option value="ESPUTO" {{ ($suspectCase->sample_type == 'ESPUTO')?'selected':'' }}>ESPUTO</option>
                <option value="TÓRULAS NASOFARÍNGEAS/ESPUTO" {{ ($suspectCase->sample_type == 'TÓRULAS NASOFARÍNGEAS/ESPUTO')?'selected':'' }}>TÓRULAS NASOFARÍNGEAS/ESPUTO</option>
                <option value="ASPIRADO NASOFARÍNGEO" {{ ($suspectCase->sample_type == 'ASPIRADO NASOFARÍNGEO')?'selected':'' }}>ASPIRADO NASOFARÍNGEO</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_origin">Origen</label>
            <select name="origin" id="for_origin" class="form-control">
                <option value=""></option>
                @foreach($sampleOrigins as $sampleOrigin)
                    <option value="{{ $sampleOrigin->name }}" {{ ($suspectCase->origin == $sampleOrigin->name)?'selected':'' }}>
                        {{ $sampleOrigin->alias }}
                    </option>
                @endforeach
            </select>
        </fieldset>

        <fieldset class="form-group col-4 col-md-1">
            <label for="for_age">Edad</label>
            <input type="number" class="form-control" id="for_age" name="age"
                value="{{ $suspectCase->age }}">
        </fieldset>

        <fieldset class="form-group col-4 col-md-1">
            <label for="for_symptoms">Sintomas</label>
            <select name="symptoms" id="for_symptoms" class="form-control">
                <option value=""></option>
                <option value="Si" {{ ($suspectCase->symptoms == 'Si') ? 'selected' : '' }}>Si</option>
                <option value="No" {{ ($suspectCase->symptoms == 'No') ? 'selected' : '' }}>No</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-4 col-md-2">
            <label for="for_laboratory_id">Laboratorio</label>
            <select name="laboratory_id" id="for_laboratory_id" class="form-control">
                <option value="1" {{ ($suspectCase->laboratory_id == 1)?'selected':'' }}>HETG</option>
                <option value="2" {{ ($suspectCase->laboratory_id == 2)?'selected':'' }}>UNAP</option>
                <option value="3" {{ ($suspectCase->laboratory_id == 3)?'selected':'' }}>BIOCLINIC</option>
            </select>
        </fieldset>


    </div>

@can('SuspectCase: tecnologo')
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
                <option value="No solicitado"
                    {{ ($suspectCase->result_ifd == 'No solicitado')?'selected':'' }}>
                    No solicitado
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
            <label for="for_pscr_sars_cov_2_at">Fecha Resultado PCR</label>
            <input type="date" class="form-control" id="for_pscr_sars_cov_2_at"
                name="pscr_sars_cov_2_at" value="{{ isset($suspectCase->pscr_sars_cov_2_at)? $suspectCase->pscr_sars_cov_2_at->format('Y-m-d'):'' }}"
                @if(($suspectCase->pscr_sars_cov_2_at AND auth()->user()->cannot('SuspectCase: edit tecnologo'))) disabled @endif>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2 alert-danger">
            <label for="for_pscr_sars_cov_2">PCR SARS-Cov2</label>
            <select name="pscr_sars_cov_2" id="for_pscr_sars_cov_2"
                class="form-control" @if(($suspectCase->pscr_sars_cov_2 != 'pending' AND auth()->user()->cannot('SuspectCase: edit tecnologo'))) disabled @endif>
                <option value="pending" {{ ($suspectCase->pscr_sars_cov_2 == 'pending')?'selected':'' }}>Pendiente</option>
                <option value="negative" {{ ($suspectCase->pscr_sars_cov_2 == 'negative')?'selected':'' }}>Negativo</option>
                <option value="positive" {{ ($suspectCase->pscr_sars_cov_2 == 'positive')?'selected':'' }}>Positivo</option>
                <option value="rejected" {{ ($suspectCase->pscr_sars_cov_2 == 'rejected')?'selected':'' }}>Rechazado</option>
                <option value="undetermined" {{ ($suspectCase->pscr_sars_cov_2 == 'undetermined')?'selected':'' }}>Indeterminado</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_sent_isp_at">Fecha envío lab externo</label>
            <input type="date" class="form-control" id="for_sent_isp_at"
                name="sent_isp_at" value="{{ isset($suspectCase->sent_isp_at)? $suspectCase->sent_isp_at->format('Y-m-d'):'' }}">
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_external_laboratory">Laboratorio externo</label>
            <select name="external_laboratory" id="for_external_laboratory" class="form-control">
                <option value=""></option>
                <option value="Hospital Lucio Córdova" {{ ($suspectCase->external_laboratory == 'Hospital Lucio Córdova')?'selected':'' }}>Hospital Lucio Córdova</option>
                <option value="Centro Oncologico del Norte" {{ ($suspectCase->external_laboratory == 'Centro Oncologico del Norte')?'selected':'' }}>Centro Oncologico del Norte</option>
                <option value="Instituto de Salud Pública" {{ ($suspectCase->external_laboratory == 'Instituto de Salud Pública')?'selected':'' }}>Instituto de Salud Pública</option>
                <option value="Barnafi Krause" {{ ($suspectCase->external_laboratory == 'Barnafi Krause')?'selected':'' }}>Barnafi Krause</option>
                <option value="Laboratorio Médico Bioclinic" {{ ($suspectCase->external_laboratory == 'Laboratorio Médico Bioclinic')?'selected':'' }}>Laboratorio Médico Bioclinic</option>
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

        <fieldset class="form-group col-md-2">
            <label class="form-check-label" for="for_gestation">Gestante</label>
            <br><br>
            <input type="checkbox" class="form-check-input ml-3" name="gestation"
                id="for_gestation" {{ ($suspectCase->gestation)?'checked':'' }}>

        </fieldset>

        <fieldset class="form-group col-md-2">
            <label for="for_gestation_week">Semanas de gestación</label>
            <input type="text" class="form-control" name="gestation_week"
                id="for_gestation_week" value="{{ $suspectCase->gestation_week }}">
        </fieldset>

        <fieldset class="form-group col-8 col-md-4">
            <label for="for_status">Estado</label>
            <p>
                <strong>{{ $suspectCase->patient->status }}</strong>
                @can('Patient: edit')
                <a href="{{ route('patients.edit',$suspectCase->patient)}}"> Cambiar </a>
                @endcan
            </p>
        </fieldset>

    </div>

    <div class="form-group">

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="for_close_contact"
                name="close_contact" {{ ($suspectCase->close_contact) ? 'checked' : '' }}>
            <label class="form-check-label" for="for_close_contact">Contacto directo</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="for_discharge_test"
                name="discharge_test" {{ ($suspectCase->discharge_test) ? 'checked' : '' }}>
            <label class="form-check-label" for="for_discharge_test">Test de salida</label>
        </div>

    </div>

    <div class="form-row">

        <fieldset class="form-group col-12 col-md-8">
            <label for="for_observation">Observación</label>
            <input type="text" class="form-control" name="observation"
                id="for_observation" value="{{ $suspectCase->observation }}">
        </fieldset>

    </div>

    <div>
      @if($suspectCase->files != null)
          @foreach($suspectCase->files as $file)
              <a href="{{ route('lab.suspect_cases.download', $file->id) }}"
                  target="_blank" data-toggle="tooltip" data-placement="top"
                  data-original-title="{{ $file->name }}">
                  {{$file->name}}<i class="fas fa-paperclip"></i>&nbsp
              </a>
              @can('SuspectCase: file delete')
              <a href="{{ route('lab.suspect_cases.fileDelete', $file->id) }}" onclick="return confirm('Estás seguro Cesar?')">
                  [ Borrar archivo ]
              </a>
              @endcan
          @endforeach
      @endif
    </div>

    <div class="form-row">
      <fieldset class="form-group col-5">
          <label for="forFile">Adjuntar archivos</label>
          <input type="file" class="form-control-file" id="forfile" name="forfile[]" multiple>
      </fieldset>
    </div>

    <hr>

@endcan

@can('SuspectCase: seremi')
    <h4>Entrega de resultados a paciente</h4>

    <div class="form-row">

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_notification_at">Fecha de notificación</label>
            <input type="date" class="form-control" name="notification_at"
                id="for_notification_at" value="{{ ($suspectCase->notification_at)?$suspectCase->notification_at->format('Y-m-d'):'' }}">
        </fieldset>

        <fieldset class="form-group col-6 col-md-3">
            <label for="for_notification_mechanism">Mecanismo de Notificación</label>
            <select name="notification_mechanism" id="for_notification_mechanism" class="form-control">
                <option></option>
                <option value="Pendiente"
                    {{ ($suspectCase->notification_mechanism == 'Pendiente')?'selected':'' }}>
                    Pendiente</option>
                <option value="Llamada telefónica"
                    {{ ($suspectCase->notification_mechanism == 'Llamada telefónica')?'selected':'' }}>
                    Llamada telefónica</option>
                <option value="Correo electrónico"
                    {{ ($suspectCase->notification_mechanism == 'Correo electrónico')?'selected':'' }}>
                    Correo electrónico</option>
                <option value="Visita domiciliaria"
                    {{ ($suspectCase->notification_mechanism == 'Visita domiciliaria')?'selected':'' }}>
                    Visita domiciliaria</option>
                <option value="Centro de salud"
                    {{ ($suspectCase->notification_mechanism == 'Centro de salud')?'selected':'' }}>
                    Centro de salud</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_discharged_at">Fecha de alta</label>
            <input type="date" class="form-control" name="discharged_at"
                id="for_discharged_at" value="{{ ($suspectCase->discharged_at)?$suspectCase->discharged_at->format('Y-m-d'):'' }}">
        </fieldset>

    </div>

@endcan

    <button type="submit" class="btn btn-primary">Guardar</button>

    <a class="btn btn-outline-secondary" href="{{ route('lab.suspect_cases.index') }}">
        Cancelar
    </a>
</form>

@can('SuspectCase: delete')
<form method="POST" class="form-horizontal" action="{{ route('lab.suspect_cases.destroy',$suspectCase) }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger float-right" onclick="return confirm('¿Está seguro de eliminar esta sospecha?');">Eliminar</button>
</form>
@endcan

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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
  $(document).ready(function(){
      $("#forfile").change(function(){
        @if($suspectCase->files->count() != 0)
          document.getElementById("forfile").value = "";
          alert("Solo se permite adjuntar un archivo.");
        @endif
      });
  });
</script>

@endsection
