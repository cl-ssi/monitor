@extends('layouts.app')

@section('title', 'Listado de casos')

@section('content')

<div class="card">
  <div class="card-body">
    <h5>Carga Masiva</h5>
    <ul>
      <li>Carga Masiva de un Caso Completo (Incluye creación paciente, recepción y resultado)</li>
      <li>Para cargar solo pacientes, dejar vacío campos desde laboratorio en adelante.</li>
    </ul>
    <br>
    <form action="{{ route('lab.bulk_load.import.excel') }}" method="post" enctype="multipart/form-data">
        @csrf

        @method('POST')
        <div class="custom-file mb-3">
            <input type="file" class="custom-file-input"  name="file" required>
            <label class="custom-file-label" for="customFile" data-browse="Elegir">Seleccione el archivo excel...</label>
        </div>

        <div class="form-group">
            <label for="fordescription">Descripción:</label>
            <input type="text" class="form-control" id="fordescription" name="description">
        </div>

        <div class="mb-3">
            <button class="btn btn-primary float-right mb-3"><i class="fas fa-upload"></i> Cargar</button>
        </div>

    </form>
  </div>
</div>

<hr>
<br>
<h5 class="mb-3">Historial de cargas.</h5>

<table class="table table-sm table-bordered table-striped small">
	<thead>
		<tr class="text-center">
      <th>Fecha de Carga</th>
      <th>Usuario</th>
      <th>Descripción</th>
      <th>Fecha de carga</th>
		</tr>
	</thead>
	<tbody>
    @foreach($bulkLoadRecords as $key => $bulkLoadRecord)
    <tr>
      <td>{{ $bulkLoadRecord->id }}</td>
      <td>{{ $bulkLoadRecord->user->name }}</td>
      <td>{{ $bulkLoadRecord->description }}</td>
      <td>{{ $bulkLoadRecord->created_at->format('d-m-Y H:i:s') }}</td>
    </tr>
    @endforeach
	</tbody>
</table>



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
