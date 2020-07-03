@extends('layouts.app')

@section('title', 'Editar muestra')

@section('content')
<h3 class="mb-3">Editar  muestra</h3>

@can('Lab')
    @include('lab.exams.covid19.partials.edit')
@endcan


@can('SuspectCase: tecnologo')

@include('lab.exams.covid19.partials.show')

<hr>

<form method="POST" class="form-horizontal" action="{{ route('lab.exams.covid19.reception', $covid19) }}">
    @csrf
    @method('PUT')

    <div class="form-row">
        <fieldset class="form-group col-12 col-md-3">
            <label for="for_reception_at">Fecha de recepción</label>
            <input type="datetime-local" class="form-control" name="reception_at" id="for_reception_at"
                value="{{ ($covid19->reception_at) ? $covid19->reception_at->format('Y-m-d\TH:i:s') : date('Y-m-d\TH:i:s') }}"
                {{ ($covid19->reception_at) ? 'disabled' : '' }}>
        </fieldset>
    </div>

    <button type="submit" class="btn btn-primary">Recepcionar</button>

</form>

@if($covid19->reception_at)
<form method="POST" class="form-horizontal" action="{{ route('lab.exams.covid19.addresult', $covid19) }}"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-row">

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_result_at">Fecha resultado *</label>
            <input type="datetime-local" class="form-control" name="result_at" id="for_result_at"
                value="{{ ($covid19->result_at) ? $covid19->result_at->format('Y-m-d\TH:i:s') : date('Y-m-d\TH:i:s') }}" required>
        </fieldset>

        <fieldset class="form-group col-12 col-md-2">
            <label for="for_result">Resultado *</label>
            <select name="result" id="for_result" class="form-control" required>
                <option value=""></option>
                <option value="Positivo" {{ ($covid19->result == 'Positivo') ? 'selected' : '' }}>Positivo</option>
                <option value="Negativo" {{ ($covid19->result == 'Negativo') ? 'selected' : '' }}>Negativo</option>
                <option value="Indeterminado" {{ ($covid19->result == 'Indeterminado') ? 'selected' : '' }}>Indeterminado</option>
                <option value="Rechazado" {{ ($covid19->result == 'Rechazado') ? 'selected' : '' }}>Rechazado</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_file">Archivo</label>
            <div class="custom-file">
                <input type="file" name="file" class="custom-file-input" id="customFileLang" lang="es">
                <label class="custom-file-label" for="customFileLang">Seleccionar Archivo</label>
            </div>
        </fieldset>

    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>

</form>
@endif

@endcan

@endsection

@section('custom_js')
<script type="application/javascript">
    $('input[type="file"]').change(function(e){
        var fileName = e.target.files[0].name;
        $('.custom-file-label').html(fileName);
    });

    jQuery(document).ready(function($){
        // cuando cambia region
        jQuery('#regiones_demo').change(function () {
            var valorRegion = jQuery(this).val();
            var htmlComuna = '<option value="sin-comuna">Seleccione comuna</option><option value="sin-comuna">--</option>';
            @foreach ($communes as $key => $commune)
            if (valorRegion == '{{$commune->region_id}}') {
                htmlComuna = htmlComuna + '<option value="' + '{{$commune->id}}' + '">' + '{{$commune->name}}' + '</option>';
            }
            @endforeach
            jQuery('#for_commune_id').html(htmlComuna);
        });

        // cuando cambia region origen
        jQuery('#regiones').change(function () {
            var valorRegion = jQuery(this).val();
            var htmlComuna = '<option value="sin-comuna">Seleccione comuna</option><option value="sin-comuna">--</option>';
            @foreach ($communes as $key => $commune)
            if (valorRegion == '{{$commune->region_id}}') {
                htmlComuna = htmlComuna + '<option value="' + '{{$commune->id}}' + '">' + '{{$commune->name}}' + '</option>';
            }
            @endforeach
            jQuery('#for_origin_commune').html(htmlComuna);
        });

        // cuando cambia comuna origen
        jQuery('#for_origin_commune').change(function () {
            var valorComuna = jQuery(this).val();
            var htmlEstablecimiento = '<option value="sin-comuna">Seleccione establecimiento</option><option value="sin-establecimiento">--</option>';
            @foreach ($establishments as $key => $establishment)
            if (valorComuna == '{{$establishment->commune_id}}') {
                htmlEstablecimiento = htmlEstablecimiento + '<option value="' + '{{$establishment->id}}' + '">' + '{{$establishment->name}}' + '</option>';
            }
            @endforeach
            jQuery('#for_establishment_id').html(htmlEstablecimiento);
        });
    });

</script>
@endsection
