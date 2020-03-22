@extends('layouts.app')

@section('title', 'Reporte COVID-19')

@section('content')
<h3 class="mb-3">Reporte COVID-19</h3>

<div class="row">
    <div class="col-12 col-sm-4">
        <table class="table table-bordered col3">
            <tbody>
                <tr>
                    <th>Total enviados a análisis</th>
                    <td>{{ $cases->count() }}</td>
                </tr>
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
                    <td>Total hospitaliazados</td>
                    <td>{{ $cases->where('status','Hospitalizado')->count() }}</td>
                </tr>
                <tr>
                    <td>Total hospitaliazados negativos</td>
                    <td>{{ $cases->where('status','Hospitalizado')->where('pscr_sars_cov_2','negative')->count() }}</td>
                </tr>
                <tr>
                    <td>Total hospitaliazados positivos</td>
                    <td>{{ $cases->where('status','Hospitalizado')->where('pscr_sars_cov_2','positive')->count() }}</td>
                </tr>
                <tr>
                    <td>Total hospitaliazados sin resultados</td>
                    <td>{{ $cases->where('status','Hospitalizado')->where('pscr_sars_cov_2','')->count() }}</td>
                </tr>
                <tr>
                    <td>Total sospechas APS</td>
                    <td>{{ $cases->whereIn('origin',['CESFAM Guzmán','Hector Reyno'])->count() }}</td>
                </tr>
                <tr>
                    <td>Total sospechas Hospital</td>
                    <td>{{ $cases->where('origin','Hospital ETG')->count() }}</td>
                </tr>
                <tr>
                    <td>Total sospechas Privados</td>
                    <td>{{ $cases->whereIn('origin',['Clínica Tarapacá','Clínica Iquique'])->count() }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('custom_js')

@endsection
