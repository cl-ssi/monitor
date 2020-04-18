@extends('layouts.app')

@section('title', 'Listado de Habitaciones')

@section('content')

@include('sanitary_residences.nav')

<h3 class="mb-3">Listado de Bookings</h3>

<a class="btn btn-primary mb-3" href="{{ route('sanitary_residences.bookings.create') }}">Crear un Booking</a>

@php ($piso = 0)

@foreach($rooms as $room)

    @if($room->floor != $piso)
        @if($piso != 0)
            </div>
            <hr>
        @endif
        <h5>Piso {{$room->floor}}</h5>
        <div class="row mt-3">
    @endif

    <div class="border text-center small m-2" style="width: 172px; height: 172px;">
        Habitación {{ $room->number }}
        <hr>

        @if($room->bookings->first())

            @foreach($room->bookings as $booking)
            @if ($booking->status == 'Residencia Sanitaria')
            <li>
                <a href="{{ route('sanitary_residences.bookings.show',$booking) }}">
                {{ $booking->patient->fullName }}
                </a>
                <br>
                ({{ $booking->patient->suspectCases->last()->age }} AÑOS)
                <!-- <a href="{{ route('sanitary_residences.bookings.excel', $booking) }}">
                <i class="fas fa-file-excel"></i>
                </a> -->
                </li>
            @endif
            @endforeach


        @endif

    </div>

    @php ($piso = $room->floor)

@endforeach



<table class="table table-sm table-responsive mt-3">
    <thead>
        <tr>
            <th>Paciente</th>
            <th>Residence</th>
            <th>Habitación</th>
            <th>Desde</th>
            <th>Hasta (Estimado)</th>
            <th>Indicaciones</th>
            <th>Observaciones</th>
            <th></th>
        </tr>
    </thead>
    <tbody class="small">
        @foreach($bookings as $booking)
        <tr>
            <td>{{ $booking->patient->fullName }}</td>
            <td>{{ $booking->room->residence->name }}</td>
            <td>{{ $booking->room->number }}</td>
            <td>{{ $booking->from }}</td>
            <td>{{ $booking->to }}</td>
            <td>{{ $booking->indications }}</td>
            <td>{{ $booking->observations }}</td>

            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')

<script type="text/javascript">
function exportF(elem) {
    var table = document.getElementById("tabla_booking");
    var html = table.outerHTML;
    var html_no_links = html.replace(/<a[^>]*>|<\/a>/g, "");//remove if u want links in your table
    var url = 'data:application/vnd.ms-excel,' + escape(html_no_links); // Set your html table into url
    elem.setAttribute("href", url);
    elem.setAttribute("download", "booking.xls"); // Choose the file name
    return false;
}

</script>
@endsection
