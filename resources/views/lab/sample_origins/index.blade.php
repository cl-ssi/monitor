@extends('layouts.app')

@section('title', 'Listado de origenes de muestra')

@section('content')
<h3 class="mb-3">Listado de origenes de muestra</h3>

<a class="btn btn-primary mb-3" href="{{ route('lab.sample_origins.create')}}">Nuevo Origen</a>

<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Alias</th>
            <th>Dirección</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($sampleOrigins as $sampleOrigin)
        <tr>
            <td>{{ $sampleOrigin->id }}</td>
            <td>{{ $sampleOrigin->name }}</td>
            <td>{{ $sampleOrigin->alias }}</td>
            <td>{{ $sampleOrigin->address }}</td>
            <td><a href="{{ route('lab.sample_origins.edit', $sampleOrigin)}}">Editar</a></td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')

@endsection
