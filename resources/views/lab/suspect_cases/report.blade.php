@extends('layouts.app')

@section('title', 'Reporte COVID-19')

@section('content')
<h3 class="mb-3">Reporte COVID-19</h3>

<div class="row">
    <div class="col-12 col-sm-4">
        <table class="table table-bordered col3">
            <tbody>
                <tr>
                    <th class="table-active">Total enviados a análisis</th>
                    <th class="table-active text-center">{{ $cases->count() }}</th>
                </tr>
                <tr>
                    <td>Total positivos</td>
                    <th class="text-danger text-center">{{ $cases->where('pscr_sars_cov_2','positive')->count() }}</th>
                </tr>
                <tr>
                    <td>Total negativos</td>
                    <td class="text-center">{{ $cases->where('pscr_sars_cov_2','negative')->count() }}</td>
                </tr>
                <tr>
                    <td>Total sin resultados</td>
                    <td class="text-center">{{ $cases->where('pscr_sars_cov_2','')->count() }}</td>
                </tr>


                <tr>
                    <th class="table-active">Total hospitalizados</th>
                    <th class="table-active text-center">{{ $cases->where('status','Hospitalizado')->count() }}</th>
                </tr>
                <tr>
                    <td>Total hospitalizados negativos</td>
                    <td class="text-center">{{ $cases->where('status','Hospitalizado')->where('pscr_sars_cov_2','negative')->count() }}</td>
                </tr>
                <tr>
                    <td>Total hospitalizados positivos</td>
                    <td class="text-center">{{ $cases->where('status','Hospitalizado')->where('pscr_sars_cov_2','positive')->count() }}</td>
                </tr>
                <tr>
                    <td>Total hospitalizados sin resultados</td>
                    <td class="text-center">{{ $cases->where('status','Hospitalizado')->where('pscr_sars_cov_2','')->count() }}</td>
                </tr>

                <tr>
                    <th class="table-active" colspan="2">Origen</th>
                </tr>

                <tr>
                    <td>Total sospechas Hospital ETG</td>
                    <td class="text-center">
                        {{ $cases->where('origin','Hospital ETG')->count() }}
                        <span class="text-danger">
                            ( {{ $cases->where('origin','Hospital ETG')->where('pscr_sars_cov_2','positive')->count() }} )
                        </span>
                    </td>
                </tr>

                <tr>
                    <td>Total sospechas APS</td>
                    <td class="text-center">
                        {{ $cases->whereIn('origin',['CESFAM Guzmán','Hector Reyno'])->count() }}
                        <span class="text-danger">
                            ( {{ $cases->whereIn('origin',['CESFAM Guzmán','Hector Reyno'])->where('pscr_sars_cov_2','positive')->count() }} )
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Total sospechas Privados</td>
                    <td class="text-center">
                        {{ $cases->whereIn('origin',['Clínica Tarapacá','Clínica Iquique'])->count() }}
                        <span class="text-danger">
                            ( {{ $cases->whereIn('origin',['Clínica Tarapacá','Clínica Iquique'])->where('pscr_sars_cov_2','positive')->count() }} )
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('custom_js')

@endsection
