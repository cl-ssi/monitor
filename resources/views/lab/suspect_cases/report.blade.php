@extends('layouts.app')

@section('title', 'Reporte COVID-19')

@section('content')
<h3 class="mb-3">Reporte COVID-19</h3>

<div class="row">
    <div class="col-12 col-sm-4">
        <table class="table table-bordered col3">
            <tbody>
                <tr>
                    <td></td>
                    <td>Total</td>
                    <td>Hom</td>
                    <td>Muj</td>
                </tr>

                <tr>
                    <th class="table-active">Enviados a análisis</th>
                    <th class="table-active text-center">
                        {{ $cases->count() }}
                    </th>
                    <th class="table-active text-center">
                        {{ $cases->where('patient.gender','male')->count() }}
                    </th>
                    <th class="table-active text-center">
                        {{ $cases->where('patient.gender','female')->count() }}
                    </th>
                </tr>

                <tr>
                    <td>Positivos</td>
                    <th class="text-danger text-center">
                        {{ $cases->where('pscr_sars_cov_2','positive')->count() }}
                    </th>
                    <th class="text-danger text-center">
                        {{ $cases->where('patient.gender','male')->where('pscr_sars_cov_2','positive')->count() }}
                    </th>
                    <th class="text-danger text-center">
                        {{ $cases->where('patient.gender','female')->where('pscr_sars_cov_2','positive')->count() }}
                    </th>
                </tr>

                <tr>
                    <td>Negativos</td>
                    <td class="text-center">
                        {{ $cases->where('pscr_sars_cov_2','negative')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $cases->where('patient.gender','male')->where('pscr_sars_cov_2','negative')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $cases->where('patient.gender','female')->where('pscr_sars_cov_2','negative')->count() }}
                    </td>
                </tr>

                <tr>
                    <td>Pendiente resultado</td>
                    <td class="text-center">
                        {{ $cases->where('pscr_sars_cov_2','pending')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $cases->where('patient.gender','male')->where('pscr_sars_cov_2','pending')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $cases->where('patient.gender','female')->where('pscr_sars_cov_2','pending')->count() }}
                    </td>
                </tr>


                <tr>
                    <th class="table-active">Hospitalizados Básicos</th>
                    <th class="table-active text-center">
                        {{ $cases->where('status','Hospitalizado Básico')->count() }}
                    </th>
                    <th class="table-active text-center">
                        {{ $cases->where('patient.gender','male')->where('status','Hospitalizado Básico')->count() }}
                    </th>
                    <th class="table-active text-center">
                        {{ $cases->where('patient.gender','female')->where('status','Hospitalizado Básico')->count() }}
                    </th>
                </tr>

                <tr>
                    <td>Negativos</td>
                    <td class="text-center">
                        {{ $cases->where('status','Hospitalizado Básico')->where('pscr_sars_cov_2','negative')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $cases->where('patient.gender','male')->where('status','Hospitalizado Básico')->where('pscr_sars_cov_2','negative')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $cases->where('patient.gender','female')->where('status','Hospitalizado Básico')->where('pscr_sars_cov_2','negative')->count() }}
                    </td>
                </tr>

                <tr>
                    <td>Positivos</td>
                    <td class="text-danger text-center">
                        {{ $cases->where('status','Hospitalizado Básico')->where('pscr_sars_cov_2','positive')->count() }}
                    </td>
                    <td class="text-danger text-center">
                        {{ $cases->where('patient.gender','male')->where('status','Hospitalizado Básico')->where('pscr_sars_cov_2','positive')->count() }}
                    </td>
                    <td class="text-danger text-center">
                        {{ $cases->where('patient.gender','female')->where('status','Hospitalizado Básico')->where('pscr_sars_cov_2','positive')->count() }}
                    </td>
                </tr>

                <tr>
                    <td>Resultado pendiente</td>
                    <td class="text-center">
                        {{ $cases->where('status','Hospitalizado Básico')->where('pscr_sars_cov_2','pending')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $cases->where('patient.gender','male')->where('status','Hospitalizado Básico')->where('pscr_sars_cov_2','pending')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $cases->where('patient.gender','female')->where('status','Hospitalizado Básico')->where('pscr_sars_cov_2','pending')->count() }}
                    </td>
                </tr>


                <tr>
                    <th class="table-active">Hospitalizados (UCI)</th>
                    <th class="table-active text-center">
                        {{ $cases->where('status','Hospitalizado Crítico')->count() }}
                    </th>
                    <th class="table-active text-center">
                        {{ $cases->where('patient.gender','male')->where('status','Hospitalizado Crítico')->count() }}
                    </th>
                    <th class="table-active text-center">
                        {{ $cases->where('patient.gender','female')->where('status','Hospitalizado Crítico')->count() }}
                    </th>
                </tr>

                <tr>
                    <td>Negativos</td>
                    <td class="text-center">
                        {{ $cases->where('status','Hospitalizado Crítico')->where('pscr_sars_cov_2','negative')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $cases->where('patient.gender','male')->where('status','Hospitalizado Crítico')->where('pscr_sars_cov_2','negative')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $cases->where('patient.gender','female')->where('status','Hospitalizado Crítico')->where('pscr_sars_cov_2','negative')->count() }}
                    </td>
                </tr>

                <tr>
                    <td>Positivos</td>
                    <td class="text-danger text-center">
                        {{ $cases->where('status','Hospitalizado Crítico')->where('pscr_sars_cov_2','positive')->count() }}
                    </td>
                    <td class="text-danger text-center">
                        {{ $cases->where('patient.gender','male')->where('status','Hospitalizado Crítico')->where('pscr_sars_cov_2','positive')->count() }}
                    </td>
                    <td class="text-danger text-center">
                        {{ $cases->where('patient.gender','female')->where('status','Hospitalizado Crítico')->where('pscr_sars_cov_2','positive')->count() }}
                    </td>
                </tr>

                <tr>
                    <td>Resultado pendiente</td>
                    <td class="text-center">
                        {{ $cases->where('status','Hospitalizado Crítico')->where('pscr_sars_cov_2','pending')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $cases->where('patient.gender','male')->where('status','Hospitalizado Crítico')->where('pscr_sars_cov_2','pending')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $cases->where('patient.gender','female')->where('status','Hospitalizado Crítico')->where('pscr_sars_cov_2','pending')->count() }}
                    </td>
                </tr>

            </tbody>
        </table>

        <table class="table table-sm table-bordered">
            <thead>
                <tr class="table-active">
                    <th>Gestantes</th>
                    <th>Positivo</th>
                    <th>Pendiente</th>
                    <th>Negativo</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">
                        {{ $cases->where('gestation','on')->count() }}
                    </td>
                    <td class="text-center text-danger">
                        {{ $cases->where('gestation','on')->where('pscr_sars_cov_2','positive')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $cases->where('gestation','on')->where('pscr_sars_cov_2','pending')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $cases->where('gestation','on')->where('pscr_sars_cov_2','negative')->count() }}
                    </td>
                </tr>
            </tbody>
        </table>



    </div>

    <div class="col-12 col-sm-4">

        <table class="table table-sm table-bordered">
            <thead>
                <tr class="table-active">
                    <th>Casos por comuna</th>
                    <th>Positivos</th>
                    <!--th>Pendientes</th>
                    <th>Negativos</th-->
                </tr>
            </thead>
            <tbody>
                <tr class="text-center">
                    <td>Alto Hospicio</td>
                    <td class="text-danger">
                        {{ $cases->where('patient.demographic.commune','Alto Hospicio')->where('pscr_sars_cov_2','positive')->count() }}
                    </td>
                    <!--td>
                        {{ $cases->where('patient.demographic.commune','Alto Hospicio')->where('pscr_sars_cov_2','pending')->count() }}
                    </td>
                    <td>
                        {{ $cases->where('patient.demographic.commune','Alto Hospicio')->where('pscr_sars_cov_2','negative')->count() }}
                    </td-->
                </tr>

                <tr class="text-center">
                    <td>Camiña</td>
                    <td class="text-danger">
                        {{ $cases->where('patient.demographic.commune','Camiña')->where('pscr_sars_cov_2','positive')->count() }}
                    </td>
                    <!--td>
                        {{ $cases->where('patient.demographic.commune','Camiña')->where('pscr_sars_cov_2','pending')->count() }}
                    </td>
                    <td>
                        {{ $cases->where('patient.demographic.commune','Camiña')->where('pscr_sars_cov_2','negative')->count() }}
                    </td-->
                </tr>

                <tr class="text-center">
                    <td>Colchane</td>
                    <td class="text-danger">
                        {{ $cases->where('patient.demographic.commune','Colchane')->where('pscr_sars_cov_2','positive')->count() }}
                    </td>
                    <!--td>
                        {{ $cases->where('patient.demographic.commune','Colchane')->where('pscr_sars_cov_2','pending')->count() }}
                    </td>
                    <td>
                        {{ $cases->where('patient.demographic.commune','Colchane')->where('pscr_sars_cov_2','negative')->count() }}
                    </td-->
                </tr>

                <tr class="text-center">
                    <td>Huara</td>
                    <td class="text-danger">
                        {{ $cases->where('patient.demographic.commune','Huara')->where('pscr_sars_cov_2','positive')->count() }}
                    </td>
                    <!--td>
                        {{ $cases->where('patient.demographic.commune','Huara')->where('pscr_sars_cov_2','pending')->count() }}
                    </td>
                    <td>
                        {{ $cases->where('patient.demographic.commune','Huara')->where('pscr_sars_cov_2','negative')->count() }}
                    </td-->
                </tr>

                <tr class="text-center">
                    <td>Iquique</td>
                    <td class="text-danger">
                        {{ $cases->where('patient.demographic.commune','Iquique')->where('pscr_sars_cov_2','positive')->count() }}
                    </td>
                    <!--td>
                        {{ $cases->where('patient.demographic.commune','Iquique')->where('pscr_sars_cov_2','pending')->count() }}
                    </td>
                    <td>
                        {{ $cases->where('patient.demographic.commune','Iquique')->where('pscr_sars_cov_2','negative')->count() }}
                    </td-->
                </tr>

                <tr class="text-center">
                    <td>Pica</td>
                    <td class="text-danger">
                        {{ $cases->where('patient.demographic.commune','Pica')->where('pscr_sars_cov_2','positive')->count() }}
                    </td>
                    <!--td>
                        {{ $cases->where('patient.demographic.commune','Pica')->where('pscr_sars_cov_2','pending')->count() }}
                    </td>
                    <td>
                        {{ $cases->where('patient.demographic.commune','Pica')->where('pscr_sars_cov_2','negative')->count() }}
                    </td-->
                </tr>

                <tr class="text-center">
                    <td>Pozo Almonte</td>
                    <td class="text-danger">
                        {{ $cases->where('patient.demographic.commune','Pozo Almonte')->where('pscr_sars_cov_2','positive')->count() }}
                    </td>
                    <!--td>
                        {{ $cases->where('patient.demographic.commune','Pozo Almonte')->where('pscr_sars_cov_2','pending')->count() }}
                    </td>
                    <td>
                        {{ $cases->where('patient.demographic.commune','Pozo Almonte')->where('pscr_sars_cov_2','negative')->count() }}
                    </td-->
                </tr>

                <tr class="text-center">
                    <td>Sin registro</td>
                    <td class="text-danger">
                        {{
                            $cases->where('pscr_sars_cov_2','positive')
                                ->whereIn('patient.demographic.commune',['sin-comuna',null])
                                ->count()
                        }}
                    </td>

                    <!--td>
                        {{
                            $cases->where('pscr_sars_cov_2','pending')
                                ->whereIn('patient.demographic.commune',['sin-comuna',null])
                                ->count()
                        }}
                    </td>
                    <td>
                        {{
                            $cases->where('pscr_sars_cov_2','negative')
                                ->whereIn('patient.demographic.commune',['sin-comuna',null])
                                ->count()
                        }}
                    </td-->
                </tr>

            </tbody>
        </table>

        <table class="table table-sm table-bordered">
            <thead>
                <tr class="table-active">
                    <th colspan="2">
                        Tasa de incidencia<br>(Casos positivos / población*) x 100.000
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Alto Hospicio (129.999*)</td>
                    <td class="text-right">
                        {{ number_format($cases->where('patient.demographic.commune','Alto Hospicio')
                            ->where('pscr_sars_cov_2','positive')->count() / 129999 * 100000 ,2) }}
                    </td>
                </tr>
                <tr>
                    <td>Camiña (1.375*)</td>
                    <td class="text-right">
                        {{ number_format($cases->where('patient.demographic.commune','Camiña')
                            ->where('pscr_sars_cov_2','positive')->count() / 1375 * 100000 ,2) }}
                    </td>
                </tr>
                <tr>
                    <td>Colchane (1.583*)</td>
                    <td class="text-right">
                        {{ number_format($cases->where('patient.demographic.commune','Colchane')
                            ->where('pscr_sars_cov_2','positive')->count() / 1583 * 100000 ,2) }}
                    </td>
                </tr>
                <tr>
                    <td>Huara (3.000*)</td>
                    <td class="text-right">
                        {{ number_format($cases->where('patient.demographic.commune','Huara')
                            ->where('pscr_sars_cov_2','positive')->count() / 3000 * 100000 ,2) }}
                    </td>
                </tr>
                <tr>
                    <td>Iquique (223.463*)</td>
                    <td class="text-right">
                        {{ number_format($cases->where('patient.demographic.commune','Iquique')
                            ->where('pscr_sars_cov_2','positive')->count() / 223463 * 100000 ,2) }}
                    </td>
                </tr>
                <tr>
                    <td>Pica (5.958*)</td>
                    <td class="text-right">
                        {{ number_format($cases->where('patient.demographic.commune','Pica')
                            ->where('pscr_sars_cov_2','positive')->count() / 5958 * 100000 ,2) }}
                    </td>
                </tr>
                <tr>
                    <td>Pozo Almonte (17.395*)</td>
                    <td class="text-right">
                        {{ number_format($cases->where('patient.demographic.commune','Pozo Almonte')
                            ->where('pscr_sars_cov_2','positive')->count() / 17395 * 100000 ,2) }}
                    </td>
                </tr>
                <tr>
                    <th>Región Tarapacá (382.773*)</th>
                    <th class="text-right">
                        {{ number_format($cases->where('pscr_sars_cov_2','positive')
                            ->count() / 382773 * 100000 ,2) }}
                    </th>
                </tr>
            </tbody>
        </table>


        <table class="table table-sm table-bordered">
            <thead>
                <tr class="table-active">
                    <th>Sospechas por Origen</th>
                    <th>Total de casos</th>
                    <th>Total posivos</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Hospital ETG</td>
                    <td class="text-center">
                        {{ $cases->where('origin','HOSPITAL Ernesto Torres Galdames')->count() }}
                    </td>
                    <td class="text-center">
                        <span class="text-danger">
                            {{ $cases->where('origin','HOSPITAL Ernesto Torres Galdames')->where('pscr_sars_cov_2','positive')->count() }}
                        </span>
                    </td>
                </tr>

                <tr>
                    <td>APS</td>
                    <td class="text-center">
                        {{ $cases->whereNotIn('origin',['HOSPITAL Ernesto Torres Galdames','Clínica Tarapacá','Clínica Iquique'])->count() }}
                    </td>
                    <td class="text-center">
                        <span class="text-danger">
                            {{ $cases->whereNotIn('origin',['HOSPITAL Ernesto Torres Galdames','Clínica Tarapacá','Clínica Iquique'])->where('pscr_sars_cov_2','positive')->count() }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Privados</td>
                    <td class="text-center">
                        {{ $cases->whereIn('origin',['Clínica Tarapacá','Clínica Iquique'])->count() }}
                    </td>
                    <td class="text-center">
                        <span class="text-danger">
                            {{ $cases->whereIn('origin',['Clínica Tarapacá','Clínica Iquique','Particular (SEREMI)'])->where('pscr_sars_cov_2','positive')->count() }}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>


    </div>

    <div class="col-12 col-sm-4">

        <table class="table table-sm table-bordered">
            <thead>
                <tr class="table-active">
                    <th>Rango Edad</th>
                    <th>Positivo</th>
                    <!--th>Pendiente</th>
                    <th>Negativo</th-->
                </tr>
            </thead>
            <tbody>
                <tr class="text-center">
                    <td>0-9</td>
                    <td class="text-danger">
                        {{
                            $cases->where('pscr_sars_cov_2','positive')
                                ->whereBetween('age',[0,9])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td>
                    <!--td>
                        {{
                            $cases->where('pscr_sars_cov_2','pending')
                                ->whereBetween('age',[0,9])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td>
                    <td>
                        {{
                            $cases->where('pscr_sars_cov_2','negative')
                                ->whereBetween('age',[0,9])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td-->
                </tr>
                <tr class="text-center">
                    <td>10-19</td>
                    <td class="text-danger">
                        {{
                            $cases->where('pscr_sars_cov_2','positive')
                                ->whereBetween('age',[10,19])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td>
                    <!--td>
                        {{
                            $cases->where('pscr_sars_cov_2','pending')
                                ->whereBetween('age',[10,19])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td>
                    <td>
                        {{
                            $cases->where('pscr_sars_cov_2','negative')
                                ->whereBetween('age',[10,19])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td-->
                </tr>
                <tr class="text-center">
                    <td>20-29</td>
                    <td class="text-danger">
                        {{
                            $cases->where('pscr_sars_cov_2','positive')
                                ->whereBetween('age',[20,29])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td>
                    <!--td>
                        {{
                            $cases->where('pscr_sars_cov_2','pending')
                                ->whereBetween('age',[20,29])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td>
                    <td>
                        {{
                            $cases->where('pscr_sars_cov_2','negative')
                                ->whereBetween('age',[20,29])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td-->
                </tr>
                <tr class="text-center">
                    <td>30-39</td>
                    <td class="text-danger">
                        {{
                            $cases->where('pscr_sars_cov_2','positive')
                                ->whereBetween('age',[30,39])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td>
                    <!--td>
                        {{
                            $cases->where('pscr_sars_cov_2','pending')
                                ->whereBetween('age',[30,39])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td>
                    <td>
                        {{
                            $cases->where('pscr_sars_cov_2','negative')
                                ->whereBetween('age',[30,39])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td-->
                </tr>
                <tr class="text-center">
                    <td>40-49</td>
                    <td class="text-danger">
                        {{
                            $cases->where('pscr_sars_cov_2','positive')
                                ->whereBetween('age',[40,49])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td>
                    <!--td>
                        {{
                            $cases->where('pscr_sars_cov_2','pending')
                                ->whereBetween('age',[40,49])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td>
                    <td>
                        {{
                            $cases->where('pscr_sars_cov_2','negative')
                                ->whereBetween('age',[40,49])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td-->
                </tr>
                <tr class="text-center">
                    <td>50-59</td>
                    <td class="text-danger">
                        {{
                            $cases->where('pscr_sars_cov_2','positive')
                                ->whereBetween('age',[50,59])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td>
                    <!--td>
                        {{
                            $cases->where('pscr_sars_cov_2','pending')
                                ->whereBetween('age',[50,59])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td>
                    <td>
                        {{
                            $cases->where('pscr_sars_cov_2','negative')
                                ->whereBetween('age',[50,59])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td-->
                </tr>
                <tr class="text-center">
                    <td>60-69</td>
                    <td class="text-danger">
                        {{
                            $cases->where('pscr_sars_cov_2','positive')
                                ->whereBetween('age',[60,69])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td>
                    <!--td>
                        {{
                            $cases->where('pscr_sars_cov_2','pending')
                                ->whereBetween('age',[60,69])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td>
                    <td>
                        {{
                            $cases->where('pscr_sars_cov_2','negative')
                                ->whereBetween('age',[60,69])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td-->
                </tr>
                <tr class="text-center">
                    <td>70-79</td>
                    <td class="text-danger">
                        {{
                            $cases->where('pscr_sars_cov_2','positive')
                                ->whereBetween('age',[70,79])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td>
                    <!--td>
                        {{
                            $cases->where('pscr_sars_cov_2','pending')
                                ->whereBetween('age',[70,79])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td>
                    <td>
                        {{
                            $cases->where('pscr_sars_cov_2','negative')
                                ->whereBetween('age',[70,79])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td-->
                </tr>
                <tr class="text-center">
                    <td>80-></td>
                    <td class="text-danger">
                        {{
                            $cases->where('pscr_sars_cov_2','positive')
                                ->whereBetween('age',[80,900])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td>
                    <!--td>
                        {{
                            $cases->where('pscr_sars_cov_2','pending')
                                ->whereBetween('age',[80,900])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td>
                    <td>
                        {{
                            $cases->where('pscr_sars_cov_2','negative')
                                ->whereBetween('age',[80,900])
                                ->whereNotNull('age')
                                ->count()
                        }}
                    </td-->
                </tr>
                <tr class="text-center">
                    <td>Sin Registro</td>
                    <td class="text-danger">
                        {{
                            $cases->where('pscr_sars_cov_2','positive')
                                ->whereNull('age')
                                ->count()
                        }}
                    </td>
                    <!--td>
                        {{
                            $cases->where('pscr_sars_cov_2','pending')
                            ->whereNull('age')
                            ->count()
                        }}
                    </td>
                    <td>
                        {{
                            $cases->where('pscr_sars_cov_2','negative')
                            ->whereNull('age')
                            ->count()
                        }}
                    </td-->
                </tr>
            </tbody>
        </table>

        <div id="curve_chart" style="width: 370px; height: 500px"></div>
    </div>
</div>

@endsection

@section('custom_js_head')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Edad',  'Casos'],
        ['0',     {{$cases->where('pscr_sars_cov_2','positive')->whereBetween('age',[0,9])->whereNotNull('age')->count()}}],
        ['10',     {{$cases->where('pscr_sars_cov_2','positive')->whereBetween('age',[10,19])->whereNotNull('age')->count()}}],
        ['20',     {{$cases->where('pscr_sars_cov_2','positive')->whereBetween('age',[20,29])->whereNotNull('age')->count()}}],
        ['30',     {{$cases->where('pscr_sars_cov_2','positive')->whereBetween('age',[30,39])->whereNotNull('age')->count()}}],
        ['40',     {{$cases->where('pscr_sars_cov_2','positive')->whereBetween('age',[40,49])->whereNotNull('age')->count()}}],
        ['50',     {{$cases->where('pscr_sars_cov_2','positive')->whereBetween('age',[50,59])->whereNotNull('age')->count()}}],
        ['60',     {{$cases->where('pscr_sars_cov_2','positive')->whereBetween('age',[60,69])->whereNotNull('age')->count()}}],
        ['70',     {{$cases->where('pscr_sars_cov_2','positive')->whereBetween('age',[70,79])->whereNotNull('age')->count()}}],
        ['80->',     {{$cases->where('pscr_sars_cov_2','positive')->whereBetween('age',[80,120])->whereNotNull('age')->count()}}],
    ]);

    var options = {
        title: 'Casos positivos por edad',
        curveType: 'function',
        legend: { position: 'bottom' },
        chartArea: {'width': '90%', 'height': '80%'},
    };

    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

    chart.draw(data, options);
    }
</script>
@endsection
