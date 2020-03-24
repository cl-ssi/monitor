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

                    <div class="alert alert-success">Telefono: +56 9 82598059</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
