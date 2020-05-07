<ul class="nav nav-tabs mb-3 d-print-none">

    <li class="nav-item">
        <a class="nav-link" href="{{ route('sanitary_residences.bookings.index',1) }}">
            <i class="fas fa-inbox"></i> Booking Agua Luna
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('sanitary_residences.bookings.index',2) }}">
            <i class="fas fa-inbox"></i> Booking Colegio UNAP
        </a>
    </li>

    @can('SanitaryResidence: admin')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('sanitary_residences.residences.index') }}">
            <i class="fas fa-hotel"></i> Residencias
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('sanitary_residences.rooms.index') }}">
            <i class="fas fa-home"></i> Habitaciones
        </a>
    </li>
    @endcan

    <li class="nav-item">
        <a class="nav-link"  href="{{ route('sanitary_residences.bookings.excelall')  }}">
            <i class="fas fa-file-excel"></i> Reporte
        </a>
    </li>

</ul>
