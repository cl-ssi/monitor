<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} @yield('title')</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Font Awesome - load everything-->
    <script defer src="{{ asset('js/font-awesome/all.min.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('custom_js_head')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
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
                                Home
                            </a>
                        </li>
                        @can('Patient: list')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('patients.index') }}">
                                Pacientes
                            </a>
                        </li>
                        @endcan

                        @can('SuspectCase: list')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('lab.suspect_cases.index') }}">
                                Casos sospechosos
                            </a>
                        </li>
                        @endcan

                        @can('Patient: georeferencing')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('patients.georeferencing') }}">
                                Georeferenciación
                            </a>
                        </li>
                        @endcan

                        @can('Admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('lab.suspect_cases.case_chart') }}">
                                Gráfico casos
                            </a>
                        </li>
                        @endcan

                        @can('Report')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('lab.suspect_cases.report') }}">
                                Reporte
                            </a>
                        </li>
                        @endcan

                        @can('Admin')
                        <li class="nav-item">
                          <a class="nav-link" href="{{ route('parameters.index') }}">
                              <i class="fas fa-cog fa-fw"></i> Mantenedores
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
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
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
            @yield('content')
        </main>
    </div>
    @yield('custom_js')
</body>
</html>
