<ul class="nav nav-tabs mb-3 d-print-none">

    @foreach(Auth::user()->residences->sortBy('id') as $residence)
    <li class="nav-item">
        <a class="nav-link" href="{{ route('sanitary_residences.bookings.index', $residence) }}">
            <i class="fas fa-hotel"></i> {{ $residence->name }}
        </a>
    </li>
    @endforeach

    <!-- <li class="nav-item">
        <a class="nav-link" href="{{ route('sanitary_residences.admission.index') }}">        
            <i class="fas fa-clipboard-check"></i> Aprobados
        </a>
    </li> -->

    
    
    


    
    <!-- <li class="nav-item">
        <a class="nav-link" href="{{ route('sanitary_residences.residences.index') }}">
            <i class="fas fa-hotel"></i> Residencias
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('sanitary_residences.rooms.index') }}">
            <i class="fas fa-home"></i> Habitaciones
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('sanitary_residences.users') }}">
            <i class="fas fa-users-cog"></i> Usuarios
        </a>
    </li> -->


    <!-- <li class="nav-item">
        <a class="nav-link"  href="{{ route('sanitary_residences.residences.statusReport') }}">
            <i class="fas fa-file-excel"></i> Consolidado Booking
        </a>
    </li> -->

    <!-- <li class="nav-item">
        <a class="nav-link" href="{{ route('sanitary_residences.bookings.bookingByDate') }}">
            <i class="fas fa-file-excel"></i> Booking Realizados por Fechas
        </a>
    </li>
    

     <li class="nav-item">
        <a class="nav-link"  href="{{ route('sanitary_residences.bookings.excelvitalsign')  }}">
            <i class="fas fa-file-excel"></i> Reporte Signos Vitales
        </a>
    </li> -->

</ul>