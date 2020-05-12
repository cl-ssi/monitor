@extends('layouts.app')

@section('title', 'Gestantes')

@section('content')
<h3 class="mb-3">Gestantes</h3>

<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th></th>
            <th>Nombre</th>
            <th>RUN o ID</th>
            <th>Edad</th>
            <th>Comuna</th>
            <th>Nacionalidad</th>
            <th>Estado</th>
            <th>Exámenes</th>
            <th>Covid-19</th>
        </tr>
    </thead>
    <tbody>
        @foreach($patients as $key => $patient)
        <tr class="{{ ($covid = $patient->suspectCases->where('pscr_sars_cov_2','positive')->count() > 0)?'table-active':'' }}">
            <td>{{ ++$key }}</td>
            <td>
                <a href="{{ route('patients.edit', $patient)}}">
                    {{ $patient->fullName }}
                </a>
            </td>
            <td>{{ $patient->identifier }}</td>
            <td>{{ $patient->age }}</td>
            <td>{{ ($patient->demographic) ? $patient->demographic->commune : '' }}</td>
            <td>{{ ($patient->demographic) ? $patient->demographic->nacionality : '' }}</td>
            <td>{{ $patient->status }}</td>
            <td>
                @foreach($patient->suspectCases as $case)
                <a href="{{ route('lab.suspect_cases.edit', $case) }}">
                    {{ $case->id }}
                </a> ,
                @endforeach
            </td>
            <td>{{ ($covid > 0) ? 'Positivo':'Negativo'}}</td>
        </tr>
        @endforeach
    </tbody>
</table>





@endsection

@section('custom_js')

@endsection
