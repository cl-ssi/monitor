@extends('layouts.app')

@section('title', 'Listado de Habitaciones')

@section('content')

@include('sanitary_residences.nav')

<h3 class="mb-3">Listado de Bookings</h3>

<a class="btn btn-primary mb-3" href="{{ route('sanitary_residences.bookings.create') }}">Crear un Booking</a>


<h3>{{ $residence->name }}</h3>

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


    <div class="border text-center small m-2" style="width:{{$residence->width}}px; height: {{$residence->height}}px;">
        Habitación {{ $room->number }}
        <hr>

        @if($room->bookings->first())

        @foreach($room->bookings as $booking)
        @if ($booking->status == 'Residencia Sanitaria' and $booking->patient->status == 'Residencia Sanitaria')
        <li>
            <a href="{{ route('sanitary_residences.bookings.show',$booking) }}">
                {{ $booking->patient->fullName }}
            </a>
            <br>
            ({{ $booking->patient->age }} AÑOS)
            <!-- <a href="{{ route('sanitary_residences.bookings.excel', $booking) }}">
                        <i class="fas fa-file-excel"></i>
                        </a> -->
            @can('SanitaryResidence: admin')
            <form method="POST" class="form-horizontal" action="{{ route('sanitary_residences.bookings.destroy', $booking) }}">
                @csrf
                @method('DELETE')                
                <button type="submit" class="btn" onclick="return confirm('¿Está seguro que desea ELIMINAR el Booking del paciente {{ $booking->patient->fullName }} para la habitación {{$room->number}}? ' )"><i class="fas fa-trash-alt"></i></button>
            </form>
            @endcan
        </li>
        @endif
        @endforeach

        @endif

    </div>

    @php ($piso = $room->floor)

    @endforeach

</div>
<hr>



<table class="table table-sm table-responsive mt-3">
    <thead>
        <tr>
            <th>Paciente Egresado</th>
            <th>Estado</th>
            <th>Causal de Egreso</th>
            <th>Residencial</th>
            <th>Habitación</th>
            <th>Desde</th>
            <th>Fecha Egreso</th>
            <th></th>
        </tr>
    </thead>
    <tbody class="small">
        @foreach($bookings as $booking)
        @if($booking->real_to and $booking->room->residence->id==$residence->id)
        <tr>
            <td><a href="{{ route('sanitary_residences.bookings.showrelease',$booking) }}"> {{ $booking->patient->fullName }} </a></td>
            <td>{{ $booking->status }}</td>
            <td>{{ $booking->released_cause }}</td>
            <td>{{ $booking->room->residence->name }}</td>
            <td>{{ $booking->room->number }}</td>
            <td>{{ $booking->from }}</td>
            <td>{{ $booking->real_to }}</td>

            <td></td>
        </tr>
        @endif
        @endforeach

    </tbody>
</table>

@endsection

@section('custom_js')

<script type="text/javascript">
    function exportF(elem) {
        var table = document.getElementById("tabla_booking");
        var html = table.outerHTML;
        var html_no_links = html.replace(/<a[^>]*>|<\/a>/g, ""); //remove if u want links in your table
        var url = 'data:application/vnd.ms-excel,' + escape(html_no_links); // Set your html table into url
        elem.setAttribute("href", url);
        elem.setAttribute("download", "booking.xls"); // Choose the file name
        return false;
    }
</script>
@endsection