@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Bienvenido al sistema de monitoreo COVID-19 del Servicio de Slaud
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

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

            <p class="mt-3">Registro de cambios: </p>
            <ul class="text-muted">
                <li>2020-03-25 Renombardos datos de resultado IFD equivalentes al LIS</li>
            </ul>
        </div>
    </div>
</div>
@endsection
