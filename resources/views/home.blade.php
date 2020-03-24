@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Bienvenido</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Haz iniciado sesión.
                    <hr>
                    <div class="alert alert-success">
                        Whatsapp desarrollador: +56 9 82598059<br>
                        alvaro.torres@redsalud.gob.cl
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
