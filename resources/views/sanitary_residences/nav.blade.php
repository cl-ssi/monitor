<ul class="nav nav-tabs mb-3 d-print-none">

    @foreach(Auth::user()->residences->sortBy('id') as $residence)
    <li class="nav-item">
        <a class="nav-link" href="{{ route('sanitary_residences.bookings.index', $residence) }}">
            <i class="fas fa-inbox"></i> {{ $residence->name }}
        </a>
    </li>
    @endforeach

    
    @can('SanitaryResidence: admin')


        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-file-excel"></i>
                Reportería
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                <a class="dropdown-item" href="{{ route('sanitary_residences.bookings.excelall') }}">Booking Actuales</a>

                <a class="dropdown-item" href="{{ route('sanitary_residences.residences.statusReport') }}">Consolidado Booking</a>
                
                <a class="dropdown-item" href="{{ route('sanitary_residences.bookings.bookingByDate') }}">Booking Realizados por Fechas</a>
                
            </div>
        </li>








        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-cogs"></i>
                Configuración
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                <a class="dropdown-item" href="{{ route('sanitary_residences.residences.index') }}">Residencias</a>
                
                <a class="dropdown-item" href="{{ route('sanitary_residences.rooms.index') }}">Habitaciones</a>
                

                <a class="dropdown-item" href="{{ route('sanitary_residences.users') }}">Usuarios</a>
            </div>
        </li>



        

        
    @endcan

    


    
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