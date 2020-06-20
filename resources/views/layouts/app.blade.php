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
                    <i class="fas fa-home"></i> {{ config('app.name', 'Laravel') }}
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



                        @canany(['SuspectCase: admission','SuspectCase: reception','SuspectCase: own','SuspectCase: list','Patient: tracing'])
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-lungs-virus"></i>
                                Casos
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @can('SuspectCase: admission')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.admission') }}">Agregar nuevo caso</a>
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
                                @endcan

                                @can('SuspectCase: own')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.ownIndex') }}?text=&filter%5B%5D=pending">Mis exámenes</a>
                                @endcan

                                <div class="dropdown-divider"></div>

                                @can('Patient: tracing')
                                <a class="dropdown-item" href="{{ route('patients.tracings.communes') }}">Seguimiento de mis comunas</a>
                                <a class="dropdown-item" href="{{ route('patients.tracings.establishments') }}">Seguimiento de mis establecimientos</a>
                                <!-- <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.tracing_minsal') }}">Seguimiento SEREMI</a> -->
                                @endcan

                                <div class="dropdown-divider"></div>

                                @can('Patient: tracing old')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.case_tracing') }}">Seguimiento (Antiguo)</a>
                                @endcan

                            </div>
                        </li>
                        @endcan

                        <!-- @canany('Patient: tracing')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-calendar"></i>
                                Seguimiento
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @can('Patient: tracing')
                                  <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.tracing_minsal') }}">Seguimiento Minsal</a>
                                @endcan
                            </div>
                        </li>
                        @endcan -->

                        @canany(['Lab: menu'])
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-vial"></i>
                                Lab
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                                <a class="dropdown-item" href="{{ route('lab.exams.covid19.index') }}">Examenes externos</a>
                                @can('Inmuno Test: list')
                                <a class="dropdown-item" href="{{ route('lab.inmuno_tests.index') }}">Inmunoglobulinas</a>
                                @endcan

                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.minsal_ws') }}">WS Minsal</a>
                            </div>
                        </li>
                        @endcan

                        @can('Patient: georeferencing')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('patients.georeferencing') }}">
                                <i class="fas fa-globe-americas"></i>
                                Geo
                            </a>
                        </li>
                        @endcan

                        @can('Epp: list')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('epp.index') }}">
                                <i class="fas fa-head-side-mask"></i>
                                EPP
                            </a>
                        </li>
                        @endcan



                        @canany(['SanitaryResidence: user', 'SanitaryResidence: admin' ,'SanitaryResidence: admission'] )
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-shopping-basket"></i>
                                Residencia
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                                @canany(['SanitaryResidence: user', 'SanitaryResidence: admin'] )
                                <a class="dropdown-item" href="{{ route('sanitary_residences.home') }}">Residencias Sanitarias</a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{ route('sanitary_residences.bookings.excelall') }}">Booking Actuales</a>

                                @endcan

                                @can('SanitaryResidence: admin')

                                <a class="dropdown-item" href="{{ route('sanitary_residences.residences.statusReport') }}">Consolidado Booking</a>

                                <a class="dropdown-item" href="{{ route('sanitary_residences.bookings.bookingByDate') }}">Booking Realizados por Fechas</a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{ route('sanitary_residences.residences.index') }}">Mantenedor Residencias</a>

                                <a class="dropdown-item" href="{{ route('sanitary_residences.rooms.index') }}">Habitaciones</a>

                                <a class="dropdown-item" href="{{ route('sanitary_residences.users') }}">Usuarios</a>


                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{ route('sanitary_residences.admission.index') }}">Aprobados por SEREMI</a>
                                @endcan


                                @canany(['SanitaryResidence: admission','Developer'])
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('sanitary_residences.admission.inbox') }}">Bandeja SEREMI</a>
                                @endcan

                            </div>
                        </li>
                        @endcan

                        @can('Basket:')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('help_basket.index')  }}">
                                <i class="fas fa-shopping-basket"></i>
                                Canasta
                            </a>
                        </li>
                        @endcan


                        @canany(['Report: positives','Report: other','Report: historical','Report: exams with result','Report: gestants','Report: positives demographics','Report: residences','Report: positives by range'])
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-clipboard"></i>
                                Reportes
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @can('Report: positives')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.positives') }}">Reporte de positivos</a>
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

                                @can('Report: gestants')
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.gestants') }}">Gestantes</a>
                                @endcan

                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.report.diary_lab_report') }}">Cantidad de muestras diarias</a>
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.report.diary_by_lab_report') }}">Exámenes realizados por laboratorios</a>

                                @can('Report: positives demographics')
                                    <a class="dropdown-item" href="{{ route('patients.exportPositives') }}">Reporte de positivos con dirección</a>
                                @endcan

                                @can('Report: residences')
                                    <a class="dropdown-item" href="{{ route('sanitary_residences.residences.statusReport') }}">Reporte de residencias</a>
                                @endcan

                                @can('Report: positives by range')
                                    <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.positivesByDateRange') }}">Reporte de positivos por fecha</a>
                                @endcan

                                @can('Report: positives')
                                    <a class="dropdown-item" href="{{ route('lab.suspect_cases.reports.positives_own') }}">Reporte de mi comuna</a>
                                @endcan

                            </div>
                        </li>
                        @endcan

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
                                @can('Admin')
                                <a class="nav-link" href="{{ route('parameters.index') }}">
                                    <i class="fas fa-cog fa-fw"></i> Configuracion
                                </a>
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
