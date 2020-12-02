@extends('layouts.app')

@section('title', 'Recepcionar caso')

@section('content')

    @if ($errors->any())
        <div class="alert alert-warning">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-4">
            <h3 class="mb-3"><i class="fas fa-lungs-virus"></i> Recepcionar caso</h3>
        </div>
    </div>

    <form action="{{route('lab.suspect_cases.barcode_reception.reception', $suspectCase ?? '')}}" method="get">
        <div class="form-row">
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <input type="text" class="form-control" id="for_id" name="id" placeholder="Nro. de muestra" autofocus>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">Recepcionar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <div class="form-row">
                    <div class="col-12 col-md-4">
                        <b>Muestra:</b>
                        {{$suspectCase->id ?? '' }}
                    </div>
                </div>

                <div class="form-row mt-3">
                    <div class="col-12 col-md-6">
                        <b>Nombre</b>
                        {{$suspectCase->patient->fullname ?? ''}}
                    </div>
                    <div class="col-12 col-md-6">
                        <b>Run</b>
                        {{($suspectCase->patient->run ?? '') . '-' . ($suspectCase->patient->dv ?? '')}}
                    </div>
                </div>

                <div class="form-row mt-3">
                    <div class="col-12 col-md-6">
                        <b>Dirección</b>
                        {{$suspectCase->patient->demographic->fullAddress ?? ''}}
                    </div>
                    <div class="col-12 col-md-6">
                        <b>Toma de muestra</b>
                        {{$suspectCase->sample_at ?? ''}}
                    </div>
                </div>

                <div class="form-row mt-3">
                    <div class="col-12 col-md-12">
                        <b>Observación:</b>
                        {{$suspectCase->observation ?? ''}}
                    </div>
                </div>

            </div>
        </div>
    </form>

    <div class="row">

    </div>



@endsection

@section('custom_js')

@endsection
