
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} @yield('title')</title>

    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}

    <!-- Font Awesome - load everything-->
    <script defer src="{{ asset('js/font-awesome/all.min.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/ssi.css') }}" rel="stylesheet">
    @yield('custom_js_head')

    <style media="screen">
        .navbar-custom {
            background-color:
                @switch(App::environment()) @case('local') #CE9DD9;
            @break @case('testing') #B5EAD7;
            @break @case('production') #FFFFFF;
            @break @endswitch
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm navbar-custom">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/home') }}">
                    <i class="fas fa-ship"></i> {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @auth
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="{{ url('/home') }}">
                                <i class="fas fa-home"></i>
                                Home
                            </a>
                        </li> -->

                        @can('Patient: list')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('patients.index') }}">
                                <i class="fas fa-user-injured"></i>
                                Pacientes
                            </a>
                        </li>
                        @endcan



                        @canany(['SuspectCase: admission','SuspectCase: reception','SuspectCase: own','SuspectCase: list','Patient: tracing', 'SuspectCase: bulk load', 'DialysisCenter: user'])
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-lungs-virus"></i>
                                Casos
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @can('SuspectCase: admission')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.admission') }}">Agregar nuevo caso</a>

                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.search') }}">Buscar por ID</a>
                                @endcan

                                @can('SuspectCase: reception')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.reception_inbox') }}">Recepcionar muestras</a>
                                @endcan

                                <div class="dropdown-divider"></div>

                                @can('SuspectCase: list')
                                @php
                                $labs = App\Laboratory::where('external',0)->get();
                                @endphp

                                @foreach($labs as $lab)
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.index',$lab) }}?&text=&pendientes=on">Laboratorio {{ $lab->alias }}</a>
                                @endforeach


                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.index') }}?text=&pendientes=on">Todos los exámenes</a>
                                <div class="dropdown-divider"></div>
                                @endcan

                                @can('DialysisCenter: user')
                                @php
                                $dialysis = App\EstablishmentUser::where('user_id',Auth::id())->get();
                                @endphp
                                @foreach($dialysis as $dialysi)
                                @if(str_contains($dialysi->establishment->alias, 'Diálisis'))

                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.dialysis.index', $dialysi->establishment) }}">{{$dialysi->establishment->alias}}</a>

                                @endif
                                @endforeach
                                <div class="dropdown-divider"></div>
                                @endcan







                                @can('SuspectCase: own')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.ownIndex') }}?text=&filter%5B%5D=pending">Mis exámenes</a>
                                @endcan

                                <div class="dropdown-divider"></div>

                                @can('Patient: tracing')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.notificationInbox') }}">Notificación (excepto positivos)</a>

                                <a class="dropdown-item" href="{{ route('patients.tracings.communes') }}">Seguimiento de mis comunas</a>

                                <a class="dropdown-item" href="{{ route('patients.tracings.establishments') }}">Seguimiento de mis establecimientos</a>

                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.tracing_minsal') }}">Seguimiento SEREMI</a>
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.tracing_minsal_by_patient') }}">Seguimiento SEREMI por paciente</a>

                                <a class="dropdown-item" href="{{ route('patients.tracings.withoutevents') }}">Pacientes sin seguimiento</a>

                                <a class="dropdown-item" href="{{ route('patients.tracings.notifications_report') }}">Notificados en mis establecimientos</a>

                                @canany(['SocialTracing: seremi', 'SocialTracing: aps'])
                                <a class="dropdown-item" href="{{ route('patients.tracings.requests.social_index') }}">Seguimiento de solicitudes</a>
                                @endcanany
                                @endcan


                                <div class="dropdown-divider"></div>

                                @can('Patient: tracing old')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.case_tracing') }}">Seguimiento (Antiguo)</a>
                                @endcan


                            </div>
                        </li>
                        @endcan

                        @canany(['Lab: menu','SuspectCase: bulk load','SuspectCase: import results'])
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-vial"></i>
                                Lab
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                                <a class="dropdown-item" href="{{ route('lab.exams.covid19.index') }}">Exámenes externos</a>
                                @can('Inmuno Test: list')
                                <a class="dropdown-item" href="{{ route('lab.inmuno_tests.index') }}">Inmunoglobulinas</a>
                                @endcan

                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.minsal_ws') }}">WS Minsal pendientes</a>

                                @can('SuspectCase: sequencing')
                                <a class="dropdown-item" href="{{ route('sequencing.index') }}">Secuenciación</a>
                                @endcan

                                <div class="dropdown-divider"></div>

                                @can('SuspectCase: bulk load')
                                <a class="dropdown-item" href="{{ route('lab.bulk_load.index') }}">Carga Masiva</a>
                                @endcan

                                @can('SuspectCase: import results')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.index_import_results') }}">Carga Masiva Resultados</a>
                                @endcan

                                @can('SuspectCase: bulk load PNTM')
                                    <a class="dropdown-item" href="{{ route('lab.bulk_load_from_pntm.index') }}">Carga Masiva Resultados PNTM</a>
                                @endcan

                                @can('SuspectCase: bulk load PNTM')
                                    <a class="dropdown-item" href="{{ route('lab.bulk_load_from_pntm.index.no.creation') }}">Carga Masiva Resultados PNTM HETG a Bluelab</a>
                                @endcan

                                @can('SuspectCase: edit')
                                  <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.integration_hetg_monitor_pendings') }}">Integración HETG - Esmeralda - Pendientes</a>
                                @endcan


                            </div>

                        </li>
                        @endcan

                        {{--
                        @canany(['Patient: georeferencing', 'Geo: communes', 'Geo: region', 'Geo: establishments'] )
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-globe-americas"></i>
                                Geo
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @canany(['Patient: georeferencing', 'Geo: region'])
                                <a class="dropdown-item" href="{{ route('patients.georeferencing') }}">Geo Regional</a>
                                @endcan
                                @can('Geo: communes')
                                <a class="dropdown-item" href="{{ route('patients.tracings.mapbycommunes') }}">Geo Mis Comunas</a>
                                @endcan
                                @can('Geo: establishments')
                                <a class="dropdown-item" href="{{ route('patients.tracings.mapbyestablishments') }}">Geo Mis Establecimientos</a>
                                @endcan
                            </div>
                        </li>
                        @endcan
                        --}}

                        {{--
                        @can('Epp: list')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('epp.index') }}">
                                <i class="fas fa-head-side-mask"></i>
                                EPP
                            </a>
                        </li>
                        @endcan
                        --}}


                        @canany(['SanitaryResidence: user', 'SanitaryResidence: admin' ,'SanitaryResidence: admission', 'Report: residences','SanitaryResidence: view'] )
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-hotel"></i>
                                Residencia
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                                @canany(['SanitaryResidence: user', 'SanitaryResidence: admin', 'Report: residences','SanitaryResidence: view'] )
                                <a class="dropdown-item" href="{{ route('sanitary_residences.home') }}">Residencias Sanitarias</a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{ route('sanitary_residences.bookings.excelall') }}">Booking Actuales</a>

                                @endcan

                                @canany(['SanitaryResidence: admin', 'Report: residences'])

                                <a class="dropdown-item" href="{{ route('sanitary_residences.residences.statusReport') }}">Consolidado Booking</a>

                                <a class="dropdown-item" href="{{ route('sanitary_residences.bookings.bookingByDate') }}">Booking Realizados por Fechas</a>

                                <a class="dropdown-item" href="{{ route('sanitary_residences.residences.map') }}">Mapa de Residencias</a>

                                @endcan

                                @can('SanitaryResidence: admin')

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{ route('sanitary_residences.residences.index') }}">Mantenedor Residencias</a>

                                <a class="dropdown-item" href="{{ route('sanitary_residences.rooms.index') }}">Habitaciones</a>

                                <a class="dropdown-item" href="{{ route('sanitary_residences.users') }}">Usuarios</a>

                                @endcan

                                @canany(['SanitaryResidence: admin','SanitaryResidence: user'])

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{ route('sanitary_residences.admission.index') }}">Aprobados por SEREMI</a>

                                @endcan



                                @canany(['SanitaryResidence: admission','Developer'])
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('sanitary_residences.admission.inbox') }}">Bandeja SEREMI</a>
                                @endcan


                                @canany(['Patient: fusion','Developer'])
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('patients.fusion.create') }}">Fusion de Pacientes</a>
                                @endcan

                            </div>
                        </li>
                        @endcan

                        


                        @canany(['Report: positives',
                        'Report: commune',
                        'Report: hospitalized',
                        'Report: deceased',
                        'Report: other',
                        'Report: historical',
                        'Report: exams with result',
                        'Report: gestants',
                        'Report: positives demographics',
                        'Report: residences',
                        'Report: positives by range',
                        'Report: user performance',
                        'Report: more than two days',
                        'Report: suspect cases by commune',
                        'Report: positive average by commune',
                        'Report: cases with barcodes'
                        ])
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-clipboard"></i>
                                Reportes
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                                @can('Report: positives')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.positives') }}">Reporte de positivos</a>
                                @endcan

                                @can('Report: commune')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.positives_own') }}">Reporte de mi comuna</a>
                                @endcan

                                @can('Report: hospitalized')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.hospitalized') }}">Hospitalizados</a>
                                @endcan

                                @can('Report: hospitalized commune')
                                    <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.hospitalizedByUserCommunes') }}">Hospitalizados de mis comunas</a>
                                @endcan

                                @can('Report: deceased')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.deceased') }}">Fallecidos</a>
                                @endcan

                                @can('Report: other')
                                <!--a class="dropdown-item" href="{{ route('lab.suspect_cases.report.index') }}">Reporte</a-->
                                @endcan

                                @can('Report: historical')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.report.historical_report') }}">Reporte Histórico</a>
                                @endcan

                                @can('Report: exams with result')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.exams_with_result') }}">Exámenes con resultados</a>
                                @endcan

                                @can('Report: suspect cases by commune')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.suspect_case_by_commune') }}">Exámenes por comuna</a>
                                @endcan

                                @can('Report: gestants')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.gestants') }}">Gestantes</a>
                                @endcan

                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.report.diary_lab_report') }}">Cantidad de muestras diarias</a>
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.report.diary_by_lab_report') }}">Exámenes realizados por laboratorios</a>

                                @can('Report: positive average by commune')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.report.positive_average_by_commune') }}">Tasa de positividad por comunas</a>
                                @endcan

                                @can('Report: positives demographics')
                                <a class="dropdown-item" href="{{ route('patients.exportPositives') }}">Reporte de positivos con dirección</a>
                                @endcan

                                @can('Report: residences')
                                <a class="dropdown-item" href="{{ route('sanitary_residences.residences.statusReport') }}">Reporte de residencias</a>
                                @endcan

                                @can('Report: positives by range')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.positivesByDateRange') }}">Reporte de positivos por fecha</a>
                                @endcan

                                @can('Report: requires licence')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.requires_licence') }}">Reporte de pacientes que requieren licencia médica</a>
                                @endcan

                                @can('Report: user performance')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.user_performance') }}">Reporte de rendimiento usuario</a>
                                @endcan

                                @can('Report: more than two days')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.pending_more_than_two_days') }}">Reporte de casos pendientes mayores a 48 hrs.</a>
                                @endcan

                                @can('Report: cases without results')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.cases_without_results') }}">Casos sin resultados por fecha</a>
                                @endcan
                                @can('Report: cases with barcodes')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.cases_with_barcodes') }}">Casos códigos de barra</a>
                                @endcan
                                @can('Report: rapid test')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.all_rapid_tests') }}">Test Rápido</a>
                                @endcan

                            </div>
                        </li>
                        @endcan

                        @canany(['NotContacted: create',
                                'NotContacted: list'])
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-phone"></i>
                                    No Contactados
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                                    @can('NotContacted: create')
                                        <a class="dropdown-item" href="{{ route('pending_patient.create') }}">Crear paciente no contactado</a>
                                    @endcan()
                                    @can('NotContacted: list')
                                        <a class="dropdown-item" href="{{ route('pending_patient.index') }}">Listar pacientes</a>
                                    @endcan
                                </div>
                            </li>
                        @endcanany
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Iniciar Sesión</a>
                        </li>
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                @canany(['Admin','SuspectCase: origin'])                                
                                <a class="nav-link" href="{{ route('parameters.index') }}">
                                    <i class="fas fa-cog fa-fw"></i> Configuracion
                                </a>
                                @endcanany

                                <a class="dropdown-item" target="_blank" href="https://www.youtube.com/channel/UCynVYUM4qEu9eGPvM_3Z-WA">
                                    Tutoriales
                                </a>

                                @can('Developer')
                                <a class="dropdown-item" href="{{ route('patients.tracings.withouttracing') }}">Pacientes (+) sin tracing</a>

                                <a class="dropdown-item" href="{{ route('patients.tracings.cartoindex') }}">BETA Pacientes CAR que pasaron a Indice</a>
                                @endcan

                                <a class="dropdown-item" href="{{ route('users.password.show_form') }}">
                                    Cambiar clave
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    Cerrar Sesión
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4 container">

            @foreach (['danger', 'warning', 'success', 'info'] as $key)
            @if(session()->has($key))
            <div class="alert alert-{{ $key }} alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {!! session()->get($key) !!}
            </div>
            @endif
            @endforeach


            @yield('content')
        </main>
    </div>
    @auth
    <div id="watermark">{{ Auth::id() }}</div>
    @endauth

    <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/principal.js') }}"></script>
    @yield('custom_js')
</body>

</html>
