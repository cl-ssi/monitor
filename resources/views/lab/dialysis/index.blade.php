@extends('layouts.app')

@section('title', 'Index Dialisis')

@section('content')

@include('lab.dialysis.nav')

<h3 class="mb-3">Listado de Todos los Pacientes de {{$establishment->alias}} </h3>
<a class="btn btn-outline-success btn-sm mb-3" id="downloadLink" onclick="exportF(this)">Descargar en excel <i class="far fa-file-excel"></i></a>

<div class="table-responsive">
  <table class="table table-sm table-bordered text-center align-middle " id="tabla_pacientes_centro_dialisis">
    <thead>
      <tr>
        <th>Nombres</th>
        <th>Apellido Paterno</th>
        <th>Apellido Materno</th>        
        <th>RUT</th>
        <th>Fecha de Nacimiento</th>
        <th>Dirección</th>
        <th>Comuna</th>
        <th>Centro de Dialisis</th>
      </tr>
    </thead>
    <tbody>
      @foreach($dialysis_patients as $dialysis_patient)
      @if($dialysis_patient->patient->lastExam)
      <tr class="{{ ($dialysis_patient->patient->lastExam->pcr_sars_cov_2 == 'positive')?'table-danger':''}}">
      @else
      <tr>
      @endif
        <td class="text-center align-middle">{{ $dialysis_patient->patient->name }}</td>
        <td class="text-center align-middle">{{ $dialysis_patient->patient->fathers_family }}</td>
        <td class="text-center align-middle">{{ $dialysis_patient->patient->mothers_family }}</td>
        <td class="text-center align-middle">{{ $dialysis_patient->patient->identifier }}</td>
        <td class="text-center align-middle">{{ $dialysis_patient->patient->birthday->format('d-m-Y') }}</td>
        <td class="text-center align-middle">{{ $dialysis_patient->patient->demographic->fulladdress }}</td>
        <td class="text-center align-middle">{{ $dialysis_patient->patient->demographic->commune->name }}</td>
        <td class="text-center align-middle">{{ $dialysis_patient->establishment->alias }}</td>
      </tr>
      @endforeach
  </table>
</div>


@endsection

@section('custom_js')

<script type="text/javascript">
let date = new Date()
let day = date.getDate()
let month = date.getMonth() + 1
let year = date.getFullYear()
let hour = date.getHours()
let minute = date.getMinutes()
function exportF(elem) {
    var table = document.getElementById("tabla_pacientes_centro_dialisis");
    var html = table.outerHTML;
    var html_no_links = html.replace(/<a[^>]*>|<\/a>/g, "");//remove if u want links in your table
    var url = 'data:application/vnd.ms-excel,' + escape(html_no_links); // Set your html table into url
    elem.setAttribute("href", url);
    elem.setAttribute("download", "pacientes_centro_dialisis_"+day+"_"+month+"_"+year+"_"+hour+"_"+minute+".xls"); // Choose the file name
    return false;
}
</script>


@endsection