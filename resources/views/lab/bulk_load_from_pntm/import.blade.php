@extends('layouts.app')

@section('title', 'Listado de casos')

@section('content')

<div class="card">
  <div class="card-body">
      <div class="row">
          <div class="col-6">
              <h5>Carga Masiva PNTM</h5>
              <span>Carga a monitor Esmeralda los datos descargados desde planilla de PNTM</span>
              
          </div>
          <div class="col-6 text-right">
              Laboratorio: {{Auth::user()->laboratory->alias}}
          </div>
      </div>
    <br>

{{--     <ul>--}}
{{--        <li>Verificar que los registros tengan </li>--}}
{{--     </ul>--}}

    <form action="{{ route('lab.bulk_load_from_pntm.import.excel') }}" method="post" enctype="multipart/form-data">
        @csrf

        @method('POST')
        <div class="custom-file mb-3">
            <input type="file" class="custom-file-input"  name="file" required>
            <label class="custom-file-label" for="customFile" data-browse="Elegir">Seleccione el archivo excel...</label>
        </div>

{{--        <div class="form-group">--}}
{{--            <label for="fordescription">Descripción:</label>--}}
{{--            <input type="text" class="form-control" id="fordescription" name="description">--}}
{{--        </div>--}}

        <div class="mb-3">
            <button class="btn btn-primary float-right mb-3"><i class="fas fa-upload"></i> Cargar</button>
        </div>

    </form>
  </div>
</div>

{{--<div class="card">--}}
{{--  <div class="card-body">--}}
{{--    <h5>Carga Masiva PNTM sólo passaporte</h5>--}}
{{--    <br>--}}
{{--    <form action="{{ route('lab.bulk_load_from_pntm.import.excel.passport') }}" method="post" enctype="multipart/form-data">--}}
{{--        @csrf--}}

{{--        @method('POST')--}}
{{--        <div class="custom-file mb-3">--}}
{{--            <input type="file" class="custom-file-input"  name="file" required>--}}
{{--            <label class="custom-file-label" for="customFile" data-browse="Elegir">Seleccione el archivo excel...</label>--}}
{{--        </div>--}}

{{--        <div class="form-group">--}}
{{--            <label for="fordescription">Descripción:</label>--}}
{{--            <input type="text" class="form-control" id="fordescription" name="description">--}}
{{--        </div>--}}

{{--        <div class="mb-3">--}}
{{--            <button class="btn btn-primary float-right mb-3"><i class="fas fa-upload"></i> Cargar</button>--}}
{{--        </div>--}}

{{--    </form>--}}
{{--  </div>--}}
{{--</div>--}}

@endsection

@section('custom_js')

<script>
  // Add the following code if you want the name of the file appear on select
  $(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
  });
</script>

@endsection
