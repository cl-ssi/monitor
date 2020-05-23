@extends('layouts.app')

@section('title', 'Listado de muestras')

@section('content')
<h3 class="mb-3">Listado de muestras</h3>

<div class="row">

    <div class="col-4 col-md-2">
        <a class="btn btn-primary mb-3" href="{{ route('lab.exams.covid19.create') }}">
            Agregar nueva
        </a>
    </div>

    <div class="col-7 col-md-4">
        <form method="GET" class="form-horizontal" action="{{ route('lab.exams.covid19.index') }}">

            <div class="input-group">
                <input type="text" class="form-control" name="search" id="for_search"
                    placeholder="Nombre o Apellido o Rut" >
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit" id="button-addon">Buscar</button>
                </div>
            </div>

        </form>
    </div>

    <div class="col-7 col-md-5">
        <table class="table-sm table-bordered">
            <tbody>
                <tr>
                    <th>Pendientes:</th>
                    <td>{{ $exams->whereNull('result')->count() }}</td>
                    <th>Positivos:</th>
                    <td>{{ $exams->where('result','Positivo')->count() }}</td>
                    <th>Negativos</th>
                    <td>{{ $exams->where('result','Negativo')->count() }}</td>
                </tr>
            </tbody>
        </table>

    </div>
    <div class="col-12 col-md-1">
        <a class="btn btn-outline-success btn-sm" href="{{ route('lab.exams.covid19.export' )}}">Descargar</a>
    </div>
</div>

<table class="table table-sm table-bordered" id="examenes">
    <thead>
        <tr>
            <th></th>
            <th>Identificador</th>
            <th>Nombre</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Origen</th>
            <th>Fecha Muestra</th>
            <th>Fecha Recepción</th>
            <th>Fecha Resultado</th>
            <th nowrap>Resultado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($exams->reverse() as $covid19)
        <tr>
            <td>{{ $covid19->id }}</td>
            <td class="text-right" nowrap>
                <a href="{{ route('lab.exams.covid19.edit', $covid19) }}">
                    {{ $covid19->identifier }}
                </a>
            </td>
            <td nowrap>{{ $covid19->name }}</td>
            <td nowrap>{{ $covid19->fathers_family }}</td>
            <td nowrap>{{ $covid19->mothers_family }}</td>
            <td nowrap>{{ $covid19->origin }}</td>
            <td nowrap class="small">{{ $covid19->sample_at }}</td>
            <td nowrap class="small">{{ ($covid19->reception_at) ? $covid19->reception_at->format('Y-m-d') : '' }}</td>
            <td nowrap class="small">{{ $covid19->result_at }}</td>
            <td nowrap>
                @if($covid19->file)
                    <a href="{{ route('lab.exams.covid19.download', $covid19->file) }}" target="_blank">
                @endif

                {{ $covid19->result }}

                @if($covid19->file)
                    </a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')
<script type="text/javascript">
    $( "main" ).removeClass( "container" );
</script>
@endsection
