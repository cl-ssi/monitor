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
                @switch(App::environment())
                    @case('local') #CE9DD9; @break
                    @case('testing') #B5EAD7; @break
                    @case('production') #FFFFFF; @break
                @endswitch
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm navbar-custom">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/home') }}">
                                <i class="fas fa-home"></i>
                                Home
                            </a>
                        </li>
                        @can('Patient: list')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('patients.index') }}">
                                <i class="fas fa-user-injured"></i>
                                Pacientes
                            </a>
                        </li>
                        @endcan

                        @can('SuspectCase: list')
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="{{ route('lab.suspect_cases.index') }}">
                                Casos sospechosos
                            </a>
                        </li> -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-lungs-virus"></i>
                                Casos
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.hetg') }}">Laboratorio HETG</a>
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.unap') }}">Laboratorio UNAP</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('lab.suspect_cases.index') }}">Todos los Casos</a>
                            </div>
                        </li>
                        @endcan

                        @can('Patient: georeferencing')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('patients.georeferencing') }}">
                                <i class="fas fa-globe-americas"></i>
                                Georeferenciación
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

                        @can('SanitaryResidence: user')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('sanitary_residences.bookings.index') }}">
                                <i class="fas fa-hotel"></i>
                                Residencia Sanitaria
                            </a>
                        </li>
                        @endcan

                        @can('Report')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('lab.suspect_cases.report.index') }}">
                                <i class="fas fa-clipboard"></i>
                                Reporte
                            </a>
                        </li>

                        <!-- <li class="nav-item">
                            <a class="nav-link" href="{{ route('lab.suspect_cases.case_chart') }}">
                                Casos
                            </a>
                        </li> -->
                        @endcan

                        @can('Report')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('lab.suspect_cases.report.case_tracing') }}">
                                <i class="fas fa-clipboard"></i>
                                Seguimiento Casos
                            </a>
                        </li>
                        @endcan


                        @can('Admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('parameters.index') }}">
                                <i class="fas fa-cog fa-fw"></i> Configuración
                            </a>
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
