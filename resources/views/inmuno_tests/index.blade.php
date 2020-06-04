@extends('layouts.app')

@section('title', 'Listado de Inmunoglobulinas IgG IgM')
@section('content')

<h3 class="mb-3">Listado Test de Inmunoglobulinas IgG IgM</h3>

@can('Inmuno Test: create')
<div class="col-4 col-md-2">
    <a class="btn btn-primary mb-3" href="{{ route('lab.inmuno_tests.create', 'search_false') }}">
        <i class="fas fa-plus"></i> Nuevo Test
    </a>
</div>
@endcan

<div align="right">
<form method="get" action="{{ route('lab.inmuno_tests.index') }}">
    <div class="input-group mb-3 col-sm-8">
    <div class="input-group-prepend">
        <span class="input-group-text">Búsqueda</span>
    </div>

    <input class="form-control" type="text" name="search" value="{{$request->search}}" placeholder="RUN (sin dígito verificador) o OTRA IDENTIFICACION / NOMBRE">
    <div class="input-group-append">
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
    </div>
  </div>

</form>
</div>

<table class="table table-sm small table-bordered">
    <thead>
        <tr class="text-center">
            <th>N° Examen</th>
            <th>Fecha Examen</th>
            <th>Run o (ID)</th>
            <th>Nombre Completo</th>
            <th>Edad</th>
            <th>Sexo</th>
            <th>IgG</th>
            <th>IgM</th>
            <th>Control</th>
            <th>Fecha de Carga</th>
            @can('Inmuno Test: edit')
            <th></th>
            @endcan
        </tr>
    </thead>

    <tbody>
      @foreach($inmunoTests as $inmunoTest)
        <tr>
            <td class="text-center">{{ $inmunoTest->id }}</td>
            <td class="text-right">{{ $inmunoTest->register_at->format('d-m-Y H:i:s') }}</td>
            <td class="text-right">{{ $inmunoTest->patient->identifier }}</td>
            <td class="text-right">{{ $inmunoTest->patient->fullName }}</td>
            <td class="text-right">{{ $inmunoTest->patient->birthday->age }}</td>
            <td class="text-right">{{ strtoupper($inmunoTest->patient->gender[0]) }}</td>
            <td class="text-right">{{ strtoupper($inmunoTest->IgValue) }}</td>
            <td class="text-right">{{ strtoupper($inmunoTest->ImValue) }}</td>
            <td class="text-right">{{ strtoupper($inmunoTest->ControlValue) }}</td>
            <td class="text-right">{{ $inmunoTest->created_at->format('d-m-Y H:i:s') }}</td>
            @can('Inmuno Test: edit')
            <td> <a href="{{ route('lab.inmuno_tests.edit', $inmunoTest) }}">Editar</a></td>
            @endcan
        </tr>
      @endforeach
    </tbody>
</table>
{{ $inmunoTests->Links() }}

@endsection

@section('custom_js')

@endsection
