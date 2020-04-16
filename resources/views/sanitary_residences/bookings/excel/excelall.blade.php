@extends('layouts.app')

@section('title', 'Listado de Habitaciones')

@section('content')

@include('sanitary_residences.nav')

<table class="table table-sm table-bordered" border="2" id="tabla_booking">
    <thead>
        <th>N°</th>
        <th>SS Dependencia</th>
        <th>Servicio de Salud de Origen</th>
        <th>Residencia de aislamiento temporal (hotel)</th>
        <th>Nº de piso</th>
        <th>Nº de habitación</th>
        <th>Comuna (de origen)</th>
        <th>Centro de Salud (CESFAM u Hosp. de origen)</th>
        <th>Nombre y Apellido del médico tratante</th>
        <th>Rut / DNI / Pasaporte/ Otro</th>
        <th>Apellidos</th>
        <th>Nombres</th>
        <th>Sexo</th>
        <th>Fecha de Nacimiento (dd/mm/aaaa)</th>
        <th>Situación COVID-19 (Sospechoso o Confirmado)</th>
        <th>Otros antecedentes médicos (Crónicos, gestantes, otros)</th>
        <th>Prescripción médica vigente</th>
        <th>Previsión de salud</th>
        <th>Teléfono de contacto</th>
        <th>Parentesco o relación con el contacto informado</th>
        <th>FECHA DE INGRESO</th>
        <th>NACIONALIDAD</th>
        <th>CAUSAL DE ALTA</th>
        <th>FECHA DE EGRESO</th>
        <th>VACUNADO INFLUENZA</th>
        <th>EXAMEN COVID AL EGRESO</th>
        <th>OBSERVACIONES</th>
    </thead>
    <tbody>
        @foreach ($bookings as $booking)
        <tr>
            <td>{{$booking->id}}</td>
            <td>Iquique</td>
            <td>Iquique</td>
            <td>{{$booking->room->residence->name}}</td>
            <td>{{$booking->room->floor}}</td>
            <td>{{$booking->room->number}}</td>
            <td>{{ ($booking->patient->demographic)?$booking->patient->demographic->commune:'' }}</td>
            <td>{{ $booking->patient->suspectCases->last()->origin }}</td>
            <td> {{$booking->doctor}} </td>
            <td>{{$booking->patient->identifier}}</td>
            <td>{{$booking->patient->fathers_family}} {{$booking->patient->mothers_family}}</td>
            <td>{{$booking->patient->name}}</td>
            <td>{{$booking->patient->genderEsp}}</td>
            <td>{{ ($booking->patient->birthday)? $booking->patient->birthday->format('d/m/Y') :''}}</td>
            <td>{{ $booking->patient->suspectCases->first()->covid19 }}</td>
            <td>{{ $booking->morbid_history }}</td>
            <td>{{$booking->commonly_used_drugs}}</td>
            <td>{{ $booking->prevision }}</td>
            <td>{{$booking->responsible_family_member}}</td>
            <td>{{ $booking->relationship }}</td>
            <td> {{ $booking->from }} </td>
            <td></td>
            <td>{{ $booking->released_cause }}</td>
            <td>{{ $booking->real_to }}</td>
            <td></td>
            <td></td>
            <td> {{ $booking->observations }}</td>
        </tr>
        @endforeach
    </tbody>

</table>


@endsection

@section('custom_js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
function exportF(elem) {    
    alert('entre a exportF');
    var table = document.getElementById("tabla_booking");    
    var html = table.outerHTML;
    var html_no_links = html.replace(/<a[^>]*>|<\/a>/g, "");//remove if u want links in your table
    var url = 'data:application/vnd.ms-excel,' + escape(html_no_links); // Set your html table into url
    elem.setAttribute("href", url);
    elem.setAttribute("download", "booking.xls"); // Choose the file name
    return false;
}

function generate_excel(tabla_booking) {
  alert('entre a generate_exceñ');
  var table= document.getElementById(tabla_booking);
  var html = table.outerHTML;
  window.open('data:application/vnd.ms-excel;base64,' + base64_encode(html));
}

window.onload = exportF(this);
window.onload = generate_excel;

</script>
@endsection




</html>