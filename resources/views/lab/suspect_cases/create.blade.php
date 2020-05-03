@extends('layouts.app')

@section('title', 'Nueva sospecha')

@section('content')
<h3 class="mb-3">Nueva sospecha</h3>

<form method="POST" class="form-horizontal" action="{{ route('lab.suspect_cases.store') }}" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="form-row">
        <fieldset class="form-group col-10 col-md-2">
            <label for="for_run">Run</label>
            <input type="hidden" class="form-control" id="for_id" name="id">
            <input type="number" class="form-control" id="for_run" name="run">
        </fieldset>

        <fieldset class="form-group col-2 col-md-1">
            <label for="for_dv">DV</label>
            <input type="text" class="form-control" id="for_dv" name="dv" readonly>
        </fieldset>

        <fieldset class="form-group col-12 col-md-2">
            <label for="for_other_identification">Otra identificación</label>
            <input type="text" class="form-control" id="for_other_identification"
                placeholder="Extranjeros sin run" name="other_identification">
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_gender">Genero</label>
            <select name="gender" id="for_gender" class="form-control">
                <option value="male">Masculino</option>
                <option value="female">Femenino</option>
                <option value="other">Otro</option>
                <option value="unknown">Desconocido</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-3">
            <label for="for_birthday">Fecha Nacimiento</label>
            <input type="date" class="form-control" id="for_birthday"
                name="birthday">
        </fieldset>

    </div>

    <div class="form-row">
        <fieldset class="form-group col-12 col-md-4">
            <label for="for_name">Nombres</label>
            <input type="text" class="form-control" id="for_name" name="name">
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_fathers_family">Apellido Paterno</label>
            <input type="text" class="form-control" id="for_fathers_family"
                name="fathers_family">
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_mothers_family">Apellido Materno</label>
            <input type="text" class="form-control" id="for_mothers_family"
                name="mothers_family">
        </fieldset>


    </div>

    <hr>

    <div class="form-row">

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_sample_at">Fecha Muestra</label>
            <input type="date" class="form-control" id="for_sample_at"
                name="sample_at" required>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_sample_type">Tipo de Muestra</label>
            <select name="sample_type" id="for_sample_type" class="form-control">
                <option value=""></option>
                <option value="TÓRULAS NASOFARÍNGEAS">TORULAS NASOFARINGEAS</option>
                <option value="ESPUTO">ESPUTO</option>
                <option value="TÓRULAS NASOFARÍNGEAS/ESPUTO">TÓRULAS NASOFARÍNGEAS/ESPUTO</option>
                <option value="ASPIRADO NASOFARÍNGEO">ASPIRADO NASOFARÍNGEO</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_origin">Origen</label>
            <select name="origin" id="for_origin" class="form-control">
                <option value=""></option>

                <option value="HOSPITAL Ernesto Torres Galdames">HOSPITAL Ernesto Torres Galdames</option>

                <option value="ACHS">ACHS</option>
                <option value="Clínica Tarapacá">Clínica Tarapacá</option>
                <option value="Clínica Iquique">Clínica Iquique</option>
                <option value="IST">IST</option>
                <option value="Mutual de Seguridad">Mutual de Seguridad</option>
                <option value="Particular (SEREMI)">Particular (SEREMI)</option>
                <option value="Servicio Médico Legal">Servicio Médico Legal</option>
                <option value="Servicio De Salud">Servicio De Salud</option>

                <option value="Toma de Muestra Domiciliaria">Toma de Muestra Domiciliaria</option>

                <option value="Gendarmería de Chile">Gendarmería de Chile</option>
                <option value="Hogar de Ancianos">Hogar de Ancianos</option>

                <option value="CECOSF El Boro">CECOSF El Boro</option>
                <option value="CECOSF La Tortuga">CECOSF La Tortuga</option>
                <option value="CESFAM Cirujano Aguirre">CESFAM Cirujano Aguirre</option>
                <option value="CESFAM Cirujano Guzmán">CESFAM Cirujano Guzmán</option>
                <option value="CESFAM Cirujano Videla">CESFAM Cirujano Videla</option>
                <option value="CESFAM Dr. Héctor Reyno G.">CESFAM Dr. Héctor Reyno G.</option>
                <option value="CESFAM Pedro Pulgar">CESFAM Pedro Pulgar</option>
                <option value="CESFAM Pica">CESFAM Pica</option>
                <option value="CESFAM Pozo Almonte">CESFAM Pozo Almonte</option>
                <option value="CESFAM Sur de Iquique">CESFAM Sur de Iquique</option>
                <option value="CGR Camiña">CGR Camiña</option>
                <option value="CGR Colchane">CGR Colchane</option>
                <option value="CGR Huara">CGR Huara</option>
                <!-- <option value="COSAM Jorge Seguel C.">COSAM Jorge Seguel C.</option> -->
                <!-- <option value="COSAM Salvador Allende">COSAM Salvador Allende</option> -->

                <!-- <option value="PRAIS Iquique">PRAIS Iquique</option> -->
                <option value="PSR Cancosa">PSR Cancosa</option>
                <option value="PSR Cariquima">PSR Cariquima</option>
                <option value="PSR Chanavayita">PSR Chanavayita</option>
                <option value="PSR Chiapa">PSR Chiapa</option>
                <option value="PSR Enquelga">PSR Enquelga</option>
                <option value="PSR La Huayca">PSR La Huayca</option>
                <option value="PSR La Tirana">PSR La Tirana</option>
                <option value="PSR Mamiña">PSR Mamiña</option>
                <option value="PSR Matilla">PSR Matilla</option>
                <option value="PSR Moquella">PSR Moquella</option>
                <option value="PSR Pachica">PSR Pachica</option>
                <option value="PSR Pisagua">PSR Pisagua</option>
                <option value="PSR San Marcos">PSR San Marcos</option>
                <option value="PSR Sibaya">PSR Sibaya</option>
                <option value="PSR Tarapacá">PSR Tarapacá</option>
                <!-- <option value="SAPU Pedro Pulgar">SAPU Pedro Pulgar</option>
                <option value="SAPU Pozo Almonte">SAPU Pozo Almonte</option> -->
            </select>
        </fieldset>

        <fieldset class="form-group col-4 col-md-2">
            <label for="for_age">Edad</label>
            <input type="number" class="form-control" id="for_age" name="age">
        </fieldset>


    </div>

    @can('SuspectCase: tecnologo')
    <div class="form-row">

        <fieldset class="form-group col-6 col-md-2 alert-warning">
            <label for="for_result_ifd_at">Fecha Resultado IFD</label>
            <input type="date" class="form-control" id="for_result_ifd_at"
                name="result_ifd_at">
        </fieldset>

        <fieldset class="form-group col-6 col-md-2 alert-warning">
            <label for="for_result_ifd">Resultado IFD</label>
            <select name="result_ifd" id="for_result_ifd" class="form-control">
                <option ></option>
                <option value="Negativo">Negativo</option>
                <option value="Adenovirus">Adenovirus</option>
                <option value="Influenza A">Influenza A</option>
                <option value="Influenza B">Influenza B</option>
                <option value="Metapneumovirus">Metapneumovirus</option>
                <option value="Parainfluenza 1">Parainfluenza 1</option>
                <option value="Parainfluenza 2">Parainfluenza 2</option>
                <option value="Parainfluenza 3">Parainfluenza 3</option>
                <option value="VRS">VRS</option>
                <option value="No solicitado">No solicitado</option>
            </select>
        </fieldset>


        <fieldset class="form-group col-6 col-md-2 alert-warning">
            <label for="for_subtype">Subtipo</label>
            <select name="subtype" id="for_subtype" class="form-control">
                <option value=""></option>
                <option value="H1N1">H1N1</option>
                <option value="H3N2">H3N2</option>
                <option value="INF B">INF B</option>
            </select>
        </fieldset>

    </div>

    <div class="form-row">

        <fieldset class="form-group col-6 col-md-2 alert-danger">
            <label for="for_pscr_sars_cov_2_at">Fecha Resultado PCR</label>
            <input type="date" class="form-control" id="for_pscr_sars_cov_2_at"
                name="pscr_sars_cov_2_at">
        </fieldset>

        <fieldset class="form-group col-6 col-md-2 alert-danger">
            <label for="for_pscr_sars_cov_2">PCR SARS-Cov2</label>
            <select name="pscr_sars_cov_2" id="for_pscr_sars_cov_2"
                class="form-control">
                <option value="pending">Pendiente</option>
                <option value="negative">Negativo</option>
                <option value="positive">Positivo</option>
                <option value="rejected">Rechazado</option>
                <option value="undetermined">Indeterminado</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_sent_isp_at">Fecha envío lab externo</label>
            <input type="date" class="form-control" id="for_sent_isp_at"
                name="sent_isp_at">
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_external_laboratory">Laboratorio externo</label>
            <select name="external_laboratory" id="for_external_laboratory" class="form-control">
                <option value=""></option>
                <option value="Hospital Lucio Córdova">Hospital Lucio Córdova</option>
                <option value="Centro Oncologico del Norte">Centro Oncologico del Norte</option>
                <option value="Instituto de Salud Pública">Instituto de Salud Pública</option>
                <option value="Barnafi Krause">Barnafi Krause</option>
                <option value="Laboratorio Médico Bioclinic">Laboratorio Médico Bioclinic</option>
            </select>
        </fieldset>


    </div>

    <div class="form-row">

        <fieldset class="form-group col-md-2">
            <label for="for_paho_flu">PAHO FLU</label>
            <input type="number" class="form-control" name="paho_flu"
                id="for_paho_flu">
        </fieldset>

        <fieldset class="form-group col-md-2">
            <label for="for_epivigila">Epivigila</label>
            <input type="number" class="form-control" id="for_epivigila"
                name="epivigila">
        </fieldset>

        <fieldset class="form-group col-md-2">
            <label class="form-check-label" for="for_gestation">Gestante</label>
            <br><br>
            <input type="checkbox" class="form-check-input ml-3" name="gestation" id="for_gestation">

        </fieldset>

        <fieldset class="form-group col-md-2">
            <label for="for_gestation_week">Semanas de gestación</label>
            <input type="text" class="form-control" name="gestation_week"
                id="for_gestation_week">
        </fieldset>

        <fieldset class="form-group col-8 col-md-4">
            <label for="for_status">Estado</label>
            <select name="status" id="for_status" class="form-control">
                <option value=""></option>
                <option value="Hospitalizado Básico">Hospitalizado Básico</option>
                <option value="Hospitalizado Crítico">Hospitalizado Crítico</option>
                <option value="Alta">Alta</option>
                <option value="Fallecido">Fallecido</option>
                <option value="Ambulatorio">Ambulatorio (domiciliario)</option>
                <option value="Residencia Sanitaria">Residencia Sanitaria</option>
                <option value="Fugado">Fugado</option>
            </select>
        </fieldset>

    </div>

    <div class="form-row">

        <fieldset class="form-group col-md-8">
            <label for="for_observation">Observación</label>
            <input type="text" class="form-control" name="observation"
                id="for_observation">
        </fieldset>

    </div>

    <div class="form-row">
      <fieldset class="form-group col-5">
          <label for="forFile">Adjuntar archivos</label>
          <input type="file" class="form-control-file" id="forfile" name="forfile[]" multiple>
      </fieldset>
    </div>

    <hr>

    @endcan

    <button type="submit" class="btn btn-primary">Guardar</button>

    <a class="btn btn-outline-secondary" href="{{ route('lab.suspect_cases.index') }}">
        Cancelar
    </a>
</form>

@endsection

@section('custom_js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src='{{asset("js/jquery.rut.chileno.js")}}'></script>
<script type="text/javascript">
jQuery(document).ready(function($){
    //obtiene digito verificador
    $('input[name=run]').keyup(function(e) {
        var str = $("#for_run").val();
        $('#for_dv').val($.rut.dv(str));
    });

$('input[name=run]').change(function() {
    var str = $("#for_run").val();
    $.get('{{ route('patients.get')}}/'+str, function(data) {
        if(data){
            document.getElementById("for_id").value = data.id;
            document.getElementById("for_other_identification").value = data.other_identification;
            document.getElementById("for_gender").value = data.gender;
            document.getElementById("for_birthday").value = data.birthday;
            document.getElementById("for_name").value = data.name;
            document.getElementById("for_fathers_family").value = data.fathers_family;
            document.getElementById("for_mothers_family").value = data.mothers_family;
        } else {
            document.getElementById("for_id").value = "";
            document.getElementById("for_other_identification").value = "";
            document.getElementById("for_gender").value = "";
            document.getElementById("for_birthday").value = "";
            document.getElementById("for_name").value = "";
            document.getElementById("for_fathers_family").value = "";
            document.getElementById("for_mothers_family").value = "";
        }
    });
});

});

</script>

@endsection
