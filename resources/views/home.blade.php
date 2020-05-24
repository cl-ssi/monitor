@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-light">
                <div class="card-header text-center">
                    <h4>Bienvenido al sistema de monitoreo del Servicio de Salud</h4>
                </div>

                <div class="card-body text-center" style="background-color: #DADEDF;">

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <img src="{{ asset('images/esmeralda.png') }}" width="300" alt="Foto de la esmeralda">

                </div>
                <div class="card-footer text-muted text-center">
                    Sistema desarrollado por el área de Informática del
                    Servicio de Salud de Iquique
                    <a href="mailto:sistemas.ssi@redsalud.gob.cl">
                        sistemas.ssi@redsalud.gob.cl
                    </a>
                    <hr>
                    Whatsapp desarrollador: +56 9 82598059 <br>
                    <a href="mailto:alvaro.torres@redsalud.gob.cl">alvaro.torres@redsalud.gob.cl</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
