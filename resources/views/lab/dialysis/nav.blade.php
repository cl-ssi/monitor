<ul class="nav nav-tabs mb-3 d-print-none">
    <li class="nav-item">
        <a class="nav-link"  href="{{ route('lab.suspect_cases.dialysis.index',Auth::user()->dialysis_center_id) }}">
            <i class="far fa-file-alt"></i> Todos los Pacientes en Centro de Dialisis 
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link"  
            href="{{ route('lab.suspect_cases.dialysis.covid') }}">
            <i class="far fa-file-alt"></i> Pacientes en Dialisis con Covid +
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link"
            href="">
            <i class="far fa-file-alt"></i> Añadir Paciente a Dialisis
        </a>
    </li>    
</ul>