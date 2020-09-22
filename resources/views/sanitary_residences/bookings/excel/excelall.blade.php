@extends('layouts.app')

@section('title', 'Excel Booking')

@section('content')

@include('sanitary_residences.nav')



<a class="btn btn-outline-success btn-sm mb-3" id="downloadLink" onclick="exportF(this)">Descargar en excel</a>

@foreach(Auth::user()->residences as $residence)
<h6> {{ $residence->name }} {{ $bookings->where('room.residence_id',$residence->id)->count() }} </h6>

@endforeach


<table class="table table-sm table-bordered table-responsive small" id="tabla_booking">
    <thead>
        <th nowrap>N°</th>
        <th nowrap>SS Dependencia</th>
        <th nowrap>Servicio de Salud de Origen</th>
        <th nowrap>Residencia de aislamiento temporal (hotel)</th>
        <th nowrap>Nº de piso</th>
        <th nowrap>Nº de habitación</th>
        <th nowrap>Comuna (de origen)</th>
        <th nowrap>Centro de Salud (CESFAM u Hosp. de origen)</th>
        <th nowrap>Nombre y Apellido del médico tratante</th>
        <th nowrap>Rut / DNI / Pasaporte/ Otro</th>
        <th nowrap>Nombres</th>
        <th nowrap>Apellidos</th>        
        <th nowrap>Sexo</th>
        <th nowrap>Fecha de Nacimiento (dd/mm/aaaa)</th>
        <th nowrap>Edad</th>
        <th nowrap>Criterio de Ingreso</th>
        <th nowrap>Resultado Último Examen</th>
        <th nowrap>Otros antecedentes médicos (Crónicos, gestantes, otros)</th>
        <th nowrap>Prescripción médica vigente</th>
        <th nowrap>Previsión de salud</th>
        <th nowrap>Teléfono de contacto</th>
        <th nowrap>Parentesco o relación con el contacto informado</th>
        <th nowrap>FECHA DE INGRESO</th>
        <th nowrap>NACIONALIDAD</th>
        <th nowrap>CAUSAL DE ALTA</th>
        <th nowrap>FECHA DE EGRESO</th>
        <th nowrap>VACUNADO INFLUENZA</th>
        <th nowrap>EXAMEN COVID AL EGRESO</th>
        <th nowrap>OBSERVACIONES</th>
    </thead>
    <tbody>
        @php $pos=1;
        @endphp
        @foreach(Auth::user()->residences as $residence)
        @foreach($residence->rooms as $room)
        @foreach($room->bookings as $booking)
        @if ($booking->status == 'Residencia Sanitaria' and $booking->patient->status == 'Residencia Sanitaria')

        <tr>

            <td nowrap>{{$pos++}}</td>
            <td nowrap>Iquique</td>
            <td nowrap>Iquique</td>
            <td nowrap>{{$booking->room->residence->name}}</td>
            <td nowrap>{{$booking->room->floor}}</td>
            <td nowrap>{{$booking->room->number}}</td>
            <td nowrap>{{ ($booking->patient->demographic)?$booking->patient->demographic->commune->name:'' }}</td>            
            <td nowrap>{{ ($booking->patient->suspectCases->last())? $booking->patient->suspectCases->last()->establishment->alias.' - '.$booking->patient->suspectCases->last()->origin:'' }}</td>            
            <td nowrap> {{$booking->doctor}} </td>
            <td nowrap>{{$booking->patient->identifier}}</td>
            <td nowrap>{{$booking->patient->name}}</td>
            <td nowrap>{{$booking->patient->fathers_family}} {{$booking->patient->mothers_family}}</td>
            <td nowrap>{{$booking->patient->sexEsp}}</td>
            <td nowrap>{{ ($booking->patient->birthday)? $booking->patient->birthday->format('d/m/Y') :''}}</td>
            <td nowrap>{{ $booking->patient->age }}</td>
            <td nowrap>{{ $booking->entry_criteria }}</td>
            <td nowrap>{{ $booking->patient->suspectCases->last()? $booking->patient->suspectCases->last()->covid19:'' }}</td>
            <td nowrap>{{ $booking->morbid_history }}</td>
            <td nowrap>{{$booking->commonly_used_drugs}}</td>
            <td nowrap>{{ $booking->prevision }}</td>
            <td nowrap>{{$booking->responsible_family_member}}</td>
            <td nowrap>{{ $booking->relationship }}</td>
            <td nowrap> {{ $booking->from }} </td>
            <td nowrap>{{ ($booking->patient->demographic)?$booking->patient->demographic->nationality:'' }}</td>
            <td nowrap>{{ $booking->released_cause }}</td>
            <td nowrap>{{ $booking->real_to }}</td>
            <td nowrap></td>
            <td nowrap></td>
            <td nowrap> {{ $booking->observations }}</td>
        </tr>
        @endif
        @endforeach
        @endforeach
        @endforeach
    </tbody>

</table>


@endsection

@section('custom_js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
    function exportF(elem) {
        var table = document.getElementById("tabla_booking");
        var html = table.outerHTML;
        var html_no_links = html.replace(/<a[^>]*>|<\/a>/g, ""); //remove if u want links in your table
        var url = 'data:application/vnd.ms-excel,' + escape(html_no_links); // Set your html table into url
        elem.setAttribute("href", url);
        elem.setAttribute("download", "residencia_sanitaria.xls"); // Choose the file name
        return false;
    }
</script>
@endsection