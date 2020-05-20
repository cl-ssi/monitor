@extends('layouts.app')

@section('title', 'Listado de muestras')

@section('content')
<h3 class="mb-3">Listado de muestras</h3>

<a class="btn btn-primary mb-3" href="{{ route('lab.exams.covid19.create') }}">Agregar nueva</a>

<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Identificador</th>
            <th>Nombre</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Origen</th>
            <th>Fecha Muestra</th>
            <th>Fecha Resultado</th>
            <th>Resultado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($exams as $covid19)
        <tr>
            <td>
                <a href="{{ route('lab.exams.covid19.edit', $covid19) }}">
                    {{ $covid19->run }}
                </a>
            </td>
            <td>{{ $covid19->name }}</td>
            <td>{{ $covid19->fathers_family }}</td>
            <td>{{ $covid19->mothers_family }}</td>
            <td>{{ $covid19->origin }}</td>
            <td>{{ $covid19->sample_at }}</td>
            <td>{{ $covid19->result_at }}</td>
            <td>{{ $covid19->result }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')

@endsection
