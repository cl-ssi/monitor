@extends('layouts.app')

@section('title', 'Listado de Pacientes')

@section('content')

<h3 class="mb-3">Listado de archivos</h3>

<div class="table-responsive">
<table class="table table-sm">
    <thead>
        <tr>
            <th>Run o (ID)</th>
            <th>Nombre</th>
            <th>Fecha toma de muestra</th>
            <th>Fecha de resultado</th>
            <th>Archivo</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($files as $file)
        <tr>
            <td>{{ $file->suspectCase->patient->run }}</td>
            <td>{{ $file->suspectCase->patient->name }} {{ $file->suspectCase->patient->fathers_family }} {{ $file->suspectCase->patient->mothers_family }}</td>
            <td>{{ $file->suspectCase->sample_at }}</td>
            <td>{{ $file->suspectCase->result_ifd_at }}</td>
            <td><a href="{{ route('lab.suspect_cases.download', $file->id) }}"
                    target="_blank" data-toggle="tooltip" data-placement="top"
                    data-original-title="{{ $file->name }}">
                    {{$file->name}}<i class="fas fa-paperclip"></i>&nbsp
                </a></td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection

@section('custom_js')

@endsection
