@extends('layouts.app')

@section('title', 'Reporte COVID-19')

@section('content')
<h3 class="mb-3">Reporte COVID-19</h3>

<div class="row">
    <div class="col-4">
        <table class="table table-bordered col3">
            <tbody>
                <tr>
                    <td>Total positivos</td>
                    <td>{{ $cases->where('pscr_sars_cov_2','positive')->count() }}</td>
                </tr>
                <tr>
                    <td>Total negativos</td>
                    <td>{{ $cases->where('pscr_sars_cov_2','negative')->count() }}</td>
                </tr>
                <tr>
                    <td>Total sin resultados</td>
                    <td>{{ $cases->where('pscr_sars_cov_2','')->count() }}</td>
                </tr>
                <tr>
                    <th>Total enviados a análisis</th>
                    <td>{{ $cases->count() }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('custom_js')

@endsection
