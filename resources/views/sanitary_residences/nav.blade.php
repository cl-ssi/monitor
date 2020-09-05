<ul class="nav nav-tabs mb-3 d-print-none">

    @foreach(Auth::user()->residences->sortBy('id') as $residence)
    <!-- <li class="nav-item">
        <a class="nav-link" href="{{ route('sanitary_residences.bookings.index', $residence) }}">
            <i class="fas fa-hotel"></i> {{ $residence->name }}
        </a>
    </li> -->
    @endforeach

    @canany(['SanitaryResidence: admission','Developer'])

    <a class="nav-link" href="{{ route('sanitary_residences.admission.inbox') }}">
        <i class="fas fa-question"></i> Pendientes
    </a>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('sanitary_residences.admission.inboxaccept') }}">
            <i class="fas fa-clipboard-check"></i> Aprobadas
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('sanitary_residences.admission.inboxrejected') }}">
            <i class="fas fa-minus-square"></i> Rechazados
        </a>
    </li>

    @endcan

    

</ul>