<ul class="nav nav-tabs mb-3 d-print-none">

    <li class="nav-item">
        <a class="nav-link" href="{{ route('sanitary_hotels.bookings.index') }}">
            <i class="fas fa-inbox"></i> Booking
        </a>
    </li>

    @can('SanitaryHotel: admin')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('sanitary_hotels.hotels.index') }}">
            <i class="fas fa-inbox"></i> Hoteles
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('sanitary_hotels.rooms.index') }}">
            <i class="fas fa-inbox"></i> Habitaciones
        </a>
    </li>
    @endcan

</ul>
