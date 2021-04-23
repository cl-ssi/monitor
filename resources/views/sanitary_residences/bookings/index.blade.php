@extends('layouts.app')

@section('title', 'Listado de Habitaciones')

@section('content')

@include('sanitary_residences.nav')

<h3 class="mb-3">Listado de Bookings</h3>

<!-- <a class="btn btn-primary mb-3" href="{{ route('sanitary_residences.bookings.create') }}">Crear un Booking</a> -->


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


    <div class="border text-center small m-2" style="width:{{$residence->width}}px; height: {{$residence->height}}px;border-width:20px">
        Habitación {{ $room->number }}
        <br>
        @if($room->single)
        <div class="float-left">
            <i class="fa fa-bed" aria-hidden="true"></i>
            Single: {{$room->single}}
        </div>
        @endif
        @if($room->double)
        <div class="float-right">
            <i class="fa fa-bed" aria-hidden="true"></i>
            Doble: {{$room->double}}
        </div>
        @endif
        <hr style="height:2px;border-width:0;color:gray;background-color:gray">

        @if($room->bookings->first())

        @foreach($room->bookings as $booking)
        @if ($booking->status == 'Residencia Sanitaria' and $booking->patient->status == 'Residencia Sanitaria' and $booking->real_to == null)
        <li>
            <a href="{{ route('sanitary_residences.bookings.show',$booking) }}">
                {{ $booking->patient->fullName }}
            </a>
            <br>
            ({{ $booking->patient->age }} AÑOS)
            <span class="{{ ($booking->days < 14)? 'text-success':'text-danger' }}">
                ({{ $booking->days }} DÍAS EN R.S.)
            </span>

            @if($booking->patient->tracing && isset($booking->patient->tracing->quarantine_end_at))
            <span class="{{ ($booking->patient->tracing->quarantine_end_at > now())? 'text-success':'text-danger' }}">
                (Termino de Cuarentena:
                {{$booking->patient->tracing->quarantine_end_at->format('d-m-Y')}})
                @endif
                @canany(['SanitaryResidence: admin','SanitaryResidence: survey'])
                @if($booking->patient->admissionSurvey->last())
                @foreach($booking->patient->admissionSurvey as $admission)
                <a class="btn btn-success btn-sm" target=_blank href="{{ route('sanitary_residences.admission.show', $admission) }}">
                    <i class="fas fa-poll-h"></i> Ver Encuesta
                </a>
                @endforeach
                @endif
                @endcan
                <br>
                <hr>


        </li>
        @endif
        @endforeach

        @endif

    </div>

    @php ($piso = $room->floor)

    @endforeach

</div>
<hr>


<h3 class="mb-3"><i class="fas fa-user-injured"></i> Listado de Pacientes Egresados</h3>
<div class="row">
<div class="col-12 col-md-12">
        <form method="GET" class="form-horizontal" action="{{ route('sanitary_residences.bookings.index', $residence) }}">
            <div class="input-group mb-sm-0">
                <input class="form-control" type="text" name="search" autocomplete="off" id="for_search" style="text-transform: uppercase;" placeholder="RUN (sin dígito verificador) / OTRA IDENTIFICACION / NOMBRE" value="{{$request->search}}" required>
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
                </div>
            </div>
        </form>
    </div>
</div>

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
            <th>Borrar Egreso (Regresar al Booking)</th>
        </tr>
    </thead>
    <tbody class="small">
        @foreach($releases as $booking)
        <tr>
            <td><a href="{{ route('sanitary_residences.bookings.showrelease',$booking) }}"> {{ $booking->patient->fullName }} </a></td>
            <td>{{ $booking->status }}</td>
            <td>{{ $booking->released_cause }}</td>
            <td> {{ $booking->room->residence->name }}</td>
            <td>{{ $booking->room->number }}</td>
            <td>{{ $booking->from }}</td>
            <td>{{ $booking->real_to }}</td>
            <td>
                @can('SanitaryResidence: return booking')
                <form method="POST" class="form-horizontal" action="{{ route('sanitary_residences.bookings.returnbooking',$booking) }}">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro que desea REINGRESAR el Booking del paciente {{ $booking->patient->fullName }} para la habitación {{$booking->room->number}} ?' )">Reingresar al Booking</button>
                </form>
                @endcan
            </td>
        </tr>

        @endforeach

    </tbody>
</table>
{{ $releases->links() }}

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