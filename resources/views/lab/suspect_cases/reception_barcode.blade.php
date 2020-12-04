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
                    <input type="text" class="form-control" id="for_id" name="id" placeholder="Nro. de muestra"
                           autofocus>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">Recepcionar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card my-3">
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
        <h4 class="mt-3">Formularios por imprimir:</h4>
    </div>

    <form action="{{route('lab.suspect_cases.notificationFormSmallBulk')}}" method="post">
        @csrf
        @method('POST')
        <div class="row">
            @if (session('suspect_cases.received'))
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>Nro. Muestra</th>
                            <th>Nombre</th>
                            <th>Toma de Muestra</th>
                            <th>Selec.</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(session('suspect_cases.received') as $arraySuspectCase)
                            <tr>
                                <td>{{ $arraySuspectCase['id'] }}</td>
                                <td>{{$arraySuspectCase['fullName']}}</td>
                                <td>{{$arraySuspectCase['sampleAt']}}</td>
                                <td><input type="checkbox" name="selected_cases_ids[]" id="selected_cases_ids"
                                           value="{{$arraySuspectCase['id']}}" checked></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <button class="btn btn-primary mr-3" type="submit">Imprimir Formularios</button>
                <a href="{{route('lab.suspect_cases.barcode_reception.forget_cases_received')}}"
                   class="btn btn-warning">Limpiar listado de impresión</a>
            @endif
        </div>
    </form>





@endsection

@section('custom_js')

@endsection
