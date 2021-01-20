@extends('layouts.app')

@section('title', 'Listado de Test Rápido ' )

@section('content')

<h3 class="mb-3">Listado de Test Rápido</h3>

<table class="table table-sm table-bordered">
    <thead>
        <tr class="text-center">
            <th>Total</th>
            <th>Positivo</th>
            <th>Negativo</th>
            <th>Positivo Débil</th>
        </tr>
    </thead>
    <tbody>
        <tr class="text-center">
            <td>{{ $rapidtests->count() }}</td>
            <td>{{ $rapidtests->where('value_test','Positive')->count() }}</td>
            <td>{{ $rapidtests->where('value_test','Negative')->count() }}</td>
            <td>{{ $rapidtests->where('value_test','Weak Positive')->count() }}</td>
        </tr>
    </tbody>
</table>



<table class="table table-sm table-bordered small">
    <thead>
        <tr>
            <th>Run o (ID)</th>
            <th>Nombre</th>
            <th>Tipo de Examen Rápido</th>
            <th>Fecha examen</th>
            <th>Resultado Test Rápido</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($rapidtests->reverse() as $rapidtest)
        <tr>
            <td nowrap>{{ $rapidtest->patient->identifier }}</td>
            <td nowrap>
                {{ $rapidtest->patient->fullName }}
            </td>
            <td nowrap>{{ $rapidtest->type }}</td>
            <td nowrap>{{ $rapidtest->register_at }}</td>
            <td>{{ $rapidtest->valueEsp }}</td>
            <td>{{ $rapidtest->patient->suspectCases->where('sample_at',$rapidtest->register_at)->first()->pcr_sars_cov_2 }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js')

@endsection
