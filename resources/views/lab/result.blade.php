@extends('layouts.public')

@section('title', 'Resultados COVID19')

@section('content')

@if($patient)
<h3 class="mb-3">Resultado de exámenes de {{ Auth::user()->name }} {{ Auth::user()->fathers_family }}</h3>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Origen de toma de muestra</th>
            <th>Fecha de toma de muestra</th>
            <th>Resultado COVID19</th>
            <th>Fecha del resultado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($patient->suspectCases as $case)
        <tr>
            <td>{{ $case->origin }}</td>
            <td>{{ $case->sample_at->format('Y-m-d') }}</td>
            <td>{{ $case->covid19 }}</td>
            <td>{{ $case->pscr_sars_cov_2_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
    <h3>{{ Auth::user()->name }} {{ Auth::user()->fathers_family }} no registra exámenes de COVID19 en el Hospital Ernesto Torres Galdámes </h3>
@endif

@endsection

@section('custom_js')

@endsection
