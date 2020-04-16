@extends('layouts.app')

@section('title', 'Editar sospecha')

@section('content')
<h3 class="mb-3">Editar sospecha</h3>

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
            <label for="for_origin">Origen</label>
            <select name="origin" id="for_origin" class="form-control">
                <option value=""></option>
                <option value="HOSPITAL Ernesto Torres Galdames" {{ ($suspectCase->origin == 'HOSPITAL Ernesto Torres Galdames')?'selected':'' }}>HOSPITAL Ernesto Torres Galdames</option>

                <option value="Clínica Tarapacá" {{ ($suspectCase->origin == 'Clínica Tarapacá')?'selected':'' }}>Clínica Tarapacá</option>
                <option value="Clínica Iquique" {{ ($suspectCase->origin == 'Clínica Iquique')?'selected':'' }}>Clínica Iquique</option>
                <option value="Particular (SEREMI)" {{ ($suspectCase->origin == 'Particular (SEREMI)')?'selected':'' }}>Particular (SEREMI)</option>
                <option value="Servico Médico Legal" {{ ($suspectCase->origin == 'Servicio Médico Legal')?'selected':'' }}>Servicio Médico Legal</option>

                <option value="CECOSF El Boro" {{ ($suspectCase->origin == 'CECOSF El Boro')?'selected':'' }}>CECOSF El Boro</option>
                <option value="CECOSF La Tortuga" {{ ($suspectCase->origin == 'CECOSF La Tortuga')?'selected':'' }}>CECOSF La Tortuga</option>
                <option value="CESFAM Cirujano Aguirre" {{ ($suspectCase->origin == 'CESFAM Cirujano Aguirre')?'selected':'' }}>CESFAM Cirujano Aguirre</option>
                <option value="CESFAM Cirujano Guzmán" {{ ($suspectCase->origin == 'CESFAM Cirujano Guzmán')?'selected':'' }}>CESFAM Cirujano Guzmán</option>
                <option value="CESFAM Cirujano Videla" {{ ($suspectCase->origin == 'CESFAM Cirujano Videla')?'selected':'' }}>CESFAM Cirujano Videla</option>
                <option value="CESFAM Dr. Héctor Reyno G." {{ ($suspectCase->origin == 'CESFAM Dr. Héctor Reyno G.')?'selected':'' }}>CESFAM Dr. Héctor Reyno G.</option>
                <option value="CESFAM Pedro Pulgar" {{ ($suspectCase->origin == 'CESFAM Pedro Pulgar')?'selected':'' }}>CESFAM Pedro Pulgar</option>
                <option value="CESFAM Pica" {{ ($suspectCase->origin == 'CESFAM Pica')?'selected':'' }}>CESFAM Pica</option>
                <option value="CESFAM Pozo Almonte" {{ ($suspectCase->origin == 'CESFAM Pozo Almonte')?'selected':'' }}>CESFAM Pozo Almonte</option>
                <option value="CESFAM Sur de Iquique" {{ ($suspectCase->origin == 'CESFAM Sur de Iquique')?'selected':'' }}>CESFAM Sur de Iquique</option>
                <option value="CGR Camiña" {{ ($suspectCase->origin == 'CGR Camiña')?'selected':'' }}>CGR Camiña</option>
                <option value="CGR Colchane" {{ ($suspectCase->origin == 'CGR Colchane')?'selected':'' }}>CGR Colchane</option>
                <option value="CGR Huara" {{ ($suspectCase->origin == 'CGR Huara')?'selected':'' }}>CGR Huara</option>
                <!-- <option value="COSAM Jorge Seguel C." {{ ($suspectCase->origin == 'COSAM Jorge Seguel C.')?'selected':'' }}>COSAM Jorge Seguel C.</option> -->
                <!-- <option value="COSAM Salvador Allende" {{ ($suspectCase->origin == 'COSAM Salvador Allende')?'selected':'' }}>COSAM Salvador Allende</option> -->

                <!-- <option value="PRAIS Iquique" {{ ($suspectCase->origin == 'PRAIS Iquique')?'selected':'' }}>PRAIS Iquique</option> -->
                <option value="PSR Cancosa" {{ ($suspectCase->origin == 'PSR Cancosa')?'selected':'' }}>PSR Cancosa</option>
                <option value="PSR Cariquima" {{ ($suspectCase->origin == 'PSR Cariquima')?'selected':'' }}>PSR Cariquima</option>
                <option value="PSR Chanavayita" {{ ($suspectCase->origin == 'PSR Chanavayita')?'selected':'' }}>PSR Chanavayita</option>
                <option value="PSR Chiapa" {{ ($suspectCase->origin == 'PSR Chiapa')?'selected':'' }}>PSR Chiapa</option>
                <option value="PSR Enquelga" {{ ($suspectCase->origin == 'PSR Enquelga')?'selected':'' }}>PSR Enquelga</option>
                <option value="PSR La Huayca" {{ ($suspectCase->origin == 'PSR La Huayca')?'selected':'' }}>PSR La Huayca</option>
                <option value="PSR La Tirana" {{ ($suspectCase->origin == 'PSR La Tirana')?'selected':'' }}>PSR La Tirana</option>
                <option value="PSR Mamiña" {{ ($suspectCase->origin == 'PSR Mamiña')?'selected':'' }}>PSR Mamiña</option>
                <option value="PSR Matilla" {{ ($suspectCase->origin == 'PSR Matilla')?'selected':'' }}>PSR Matilla</option>
                <option value="PSR Moquella" {{ ($suspectCase->origin == 'PSR Moquella')?'selected':'' }}>PSR Moquella</option>
                <option value="PSR Pachica" {{ ($suspectCase->origin == 'PSR Pachica')?'selected':'' }}>PSR Pachica</option>
                <option value="PSR Pisagua" {{ ($suspectCase->origin == 'PSR Pisagua')?'selected':'' }}>PSR Pisagua</option>
                <option value="PSR San Marcos" {{ ($suspectCase->origin == 'PSR San Marcos')?'selected':'' }}>PSR San Marcos</option>
                <option value="PSR Sibaya" {{ ($suspectCase->origin == 'PSR Sibaya')?'selected':'' }}>PSR Sibaya</option>
                <option value="PSR Tarapacá" {{ ($suspectCase->origin == 'PSR Tarapacá')?'selected':'' }}>PSR Tarapacá</option>
                <!-- <option value="SAPU Pedro Pulgar" {{ ($suspectCase->origin == 'SAPU Pedro Pulgar')?'selected':'' }}>SAPU Pedro Pulgar</option> -->
                <!-- <option value="SAPU Pozo Almonte" {{ ($suspectCase->origin == 'SAPU Pozo Almonte')?'selected':'' }}>SAPU Pozo Almonte</option> -->

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
            <select name="status" id="for_status" class="form-control">
                <option value=""></option>
                <option value="Hospitalizado Básico" {{ ($suspectCase->status == 'Hospitalizado Básico')?'selected':'' }}>Hospitalizado Básico</option>
                <option value="Hospitalizado Crítico" {{ ($suspectCase->status == 'Hospitalizado Crítico')?'selected':'' }}>Hospitalizado Crítico</option>
                <option value="Alta" {{ ($suspectCase->status == 'Alta')?'selected':'' }}>Alta</option>
                <option value="Fallecido" {{ ($suspectCase->status == 'Fallecido')?'selected':'' }}>Fallecido</option>
                <option value="Ambulatorio" {{ ($suspectCase->status == 'Ambulatorio')?'selected':'' }}>Ambulatorio (domiciliario)</option>
                <option value="Residencia Sanitaria" {{ ($suspectCase->status == 'Residencia Sanitaria')?'selected':'' }}>Residencia Sanitaria</option>
            </select>
        </fieldset>

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

    <div class="form-row">


    <button type="submit" class="btn btn-primary">Guardar</button>

    <a class="btn btn-outline-secondary" href="{{ route('lab.suspect_cases.index') }}">
        Cancelar
    </a>
</form>

@can('SuspectCase: delete')
<form method="POST" class="form-horizontal" action="{{ route('lab.suspect_cases.destroy',$suspectCase) }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar esta sospecha?');">Eliminar</button>
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
