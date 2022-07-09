@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-light">
                <div class="card-header text-center">
                    <h4>Bienvenido al sistema de monitoreo del {{ env('SERVICIO','Configurar variable SERVICIO en .env') }}</h4>
                </div>

                <div class="card-body text-center" style="background-color: #F0F0F0;">

                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <img src="{{ asset('images/esmeralda.png') }}" width="300" alt="Foto de la esmeralda">
                    <br>
                    Usted tiene acceso a los siguientes establecimientos:
                    @foreach($establishmentsusers as $establishmentsusers)
                    <br>{{ $establishmentsusers->establishment->alias }}
                    @endforeach

                </div>

                <div class="card-footer text-muted text-center">

                    {{ env('INFO_HOME','Configurar variable INFO_HOME en .env') }}
                    <hr>
                    Sistema desarrollado por el Área de Informática del
                    Servicio de Salud de Iquique
                    <a href="mailto:sistemas.ssi@redsalud.gob.cl">
                        sistemas.ssi@redsalud.gob.cl
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection