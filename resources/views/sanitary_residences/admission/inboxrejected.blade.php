@extends('layouts.app')

@section('title', 'Home Residencias')

@section('content')

@include('sanitary_residences.nav')

<h3 class="mb-3">Listado de Encuestas Rechazadas</h3>
<a class="btn btn-outline-success btn-sm mb-3" id="downloadLink" onclick="exportF(this)">Descargar en excel <i class="far fa-file-excel"></i></a>
<div class="table-responsive">
  <table class="table table-sm table-bordered" id="tabla_encuestas_rechazadas_vb">
    <thead>
      <tr>
        <th>Rut/ID</th>
        <th>Nombre de Encuestado</th>
        <th>Dirección</th>
        <th>Fono</th>
        <th>Fecha de Encuesta</th>
        <th>Observaciones</th>
        <th>Antecedentes Morbidos</th>
        <th>Fecha Digitación en Sistema de Encuesta</th>
        <th>Encuesta Digitada en Sistema por</th>
        <th>¿Es Posible Aislar al Paciente?</th>
        <th>¿Califica Residencia?</th>
        <th>Resultado</th>
        <th>Resultado Final</th>
        <th>Ver encuesta</th>
      </tr>
    </thead>
    <tbody>
      @foreach($admissions as $admission)
      <tr>
        <td>{{$admission->patient->identifier??''}}</td>
        <td class="text-center align-middle">{{ $admission->patient->full_name }}</td>
        <td>{{($admission->patient->demographic)?$admission->patient->demographic->fulladdress:''}}</td>
        <td>{{ ($admission->patient->demographic)?$admission->patient->demographic->telephone:'' }}</td>
        <td class="text-center align-middle">{{ $admission->created_at }}</td>
        <td>{{$admission->observations??''}}</td>
        <td>{{$admission->morbid_history ?? ''}}</td>
        <td class="text-center align-middle">{{ $admission->updated_at }}</td>
        <td class="text-center align-middle">{{ $admission->user->name }}</td>
        <td class="text-center align-middle">{{ $admission->isolate_text }}</td>
        <td class="text-center align-middle">{{ $admission->residency_text }}</td>
        <td class="text-center align-middle">{{ $admission->status }}</td>
        <td class="text-center align-middle" nowrap>{!! $admission->result !!}</td>
        <td class="text-center align-middle"><a class="btn btn-success btn-sm" href="{{ route('sanitary_residences.admission.show', $admission) }}">
            <i class="fas fa-poll-h"></i> Revisar Encuesta
          </a></td>
      </tr>
      @endforeach
  </table>
</div>

@endsection




@section('custom_js')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
  let date = new Date()
  let day = date.getDate()
  let month = date.getMonth() + 1
  let year = date.getFullYear()
  let hour = date.getHours()
  let minute = date.getMinutes()

  function exportF(elem) {
    var table = document.getElementById("tabla_encuestas_rechazadas_vb");
    var html = table.outerHTML;
    var html_no_links = html.replace(/<a[^>]*>|<\/a>/g, ""); //remove if u want links in your table
    var url = 'data:application/vnd.ms-excel,' + escape(html_no_links); // Set your html table into url
    elem.setAttribute("href", url);
    elem.setAttribute("download", "encuestas_rechazadas_vb_" + day + "_" + month + "_" + year + "_" + hour + "_" + minute + ".xls"); // Choose the file name
    return false;
  }
</script>

@endsection