@extends('layouts.app')

@section('title', 'Reporte Casos Positivos')

@section('content')
<h3 class="mb-3">Reporte Casos Positivos</h3>

<div class="row">
    <div class="col-12 col-md-4 mb-3">

        <table class="table table-sm table-bordered">
            <thead>
                <tr class="table-active">
                    <th>Casos</th>
                    <th>Hom</th>
                    <th>Muj</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Positivos</td>
                    <th class="text-center">
                        {{ $total_male = $casosTotalesArray['male'] }}
                    </th>
                    <th class="text-center">
                        {{ $total_female = $casosTotalesArray['female'] }}
                    </th>
                    <th class="text-center text-danger">
                        {{ $region = $total_male + $total_female }}
                    </th>
                </tr>
            </tbody>
        </table>


        <div id="evolution" style="width: 480px; height: 400"></div>


        <!-- <table class="table table-sm table-bordered">
            <thead>
                <tr class="table-active">
                    <th colspan="5">Cantidad de Ventiladores <small>({{ $ventilator->updated_at }})</small> </th>
                    <th class="text-center">{{ $ventilator->total }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="color: red;">Covid-19+</td>
                    <th class="text-center">{{ $huciv = $UciPatients }}</th>
                    <td>No Covid-19</td>
                    <th class="text-center">{{ $ventilator->no_covid }}</th>
                    <td>Disponibles</td>
                    <th class="text-center">
                        {{ $ventilator->total - $huciv - $ventilator->no_covid }}
                    </th>
                </tr>
            </tbody>
        </table> -->

        <table class="table table-sm table-bordered">
            <thead>
                <tr class="table-active">
                    <th></th>
                    <th class="text-center">Hombres</th>
                    <th class="text-center">Mujeres</th>
                    <th class="text-center">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Fallecidos</th>
                    <td class="text-right">
                        {{ $fallecido_male = $totalDeceasedArray['male'] }}
                    </td>
                    <td class="text-right">
                        {{ $fallecido_female = $totalDeceasedArray['female'] }}
                    </td>
                    <th class="text-right">
                        {{ $fallecido_male + $fallecido_female}}
                    </th>
                </tr>
            </tbody>
        </table>

        <table class="table table-sm table-bordered">
            <thead>
                <tr class="table-active">
                    <th>Exámenes enviados a análisis (*)</th>
                    <th class="text-right">{{ $exams['total'] }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Resultados positivos (**)</td>
                    <th class="text-right">{{ $exams['positives'] }}</th>
                </tr>
                <tr>
                    <td>Resultados negativos</td>
                    <th class="text-right">{{ $exams['negatives'] }}</th>
                </tr>
                <tr>
                    <td>Resultados pendientes</td>
                    <th class="text-right">{{ $exams['pending'] }}</th>
                </tr>
                <tr>
                    <td>Resultados indeterminados</td>
                    <th class="text-right">{{ $exams['undetermined'] }}</th>
                </tr>
                <tr>
                    <td>Muestras rechazadas</td>
                    <th class="text-right">{{ $exams['rejected'] }}</th>
                </tr>
            </tbody>
        </table>
        <small>* Representa la cantidad de exámenes y no de casos, ya que un
            paciente puede tener más de un examen.</small><br>
        <small>** Representa la cantidad de exámenes positivos, que puede
            incluir segundas muestras a un mismo paciente, con motivo de dar de alta.</small>

    </div>

    <div class="col-12 col-md-4">

        <table class="table table-sm table-bordered">
            <thead>
                <tr class="table-active">
                    <th>Casos por comuna</th>
                    <th>Positivos</th>
                </tr>
            </thead>
            <tbody>


                @foreach($communes as $commune)
                <tr>
                    <td>{{ $commune->name }}</td>
                    <td class="text-center">
                        {{ $commune->count = $casesByCommuneArray[$commune->id] }}
                    </td>
                </tr>
                @endforeach

                <tr>
                    <td>Sin registro</td>
                    <td class="text-center">
                        {{ $sin_registro = $casesByCommuneArray['Sin Registro'] }}
                    </td>
                </tr>

{{--                @foreach($communes as $commune)--}}
{{--                    <tr>--}}
{{--                        <td>{{ $commune->name }}</td>--}}
{{--                        <td class="text-center">--}}
{{--                            {{ $commune->count = $patients->where('demographic.commune_id',$commune->id)->count() }}--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                @endforeach--}}

{{--                <tr>--}}
{{--                    <td>Sin registro</td>--}}
{{--                    <td class="text-center">--}}
{{--                        {{ $sin_registro = $patients->whereIn('demographic.commune',['sin-comuna',null])->count() }}--}}
{{--                    </td>--}}
{{--                </tr>--}}

            </tbody>
        </table>

        <!-- <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#exampleModal">
          <i class="fas fa-chart-line"></i> Ver Gráfica
        </button>

        <br> -->

        @include('lab.suspect_cases.reports.graphics.positives_by_commune')

        <table class="table table-sm table-bordered">
            <thead>
                <tr class="table-active">
                    <th colspan="2">
                        Tasa de incidencia<br>(Casos positivos / población*) x 100.000
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($communes as $commune)
                <tr>
                    <td>{{ $commune->name }} ({{ $commune->population }}*)</td>
                    <td class="text-right">
                        {{ number_format($commune->count / $commune->population * 100000 ,2) }}
                    </td>
                </tr>
                @endforeach

                <tr>
                    <th>Región ({{ $communes->sum('population') }}*)</th>
                    <th class="text-right">
                        {{ number_format($region / $communes->sum('population') * 100000 ,2) }}
                    </th>
                </tr>
            </tbody>
        </table>

    </div>



    <div class="col-12 col-md-4">

        <table class="table table-sm table-bordered">
            <thead>
                <tr class="table-active">
                    <th>Rango Edad</th>
                    <th>Hombres</th>
                    <th>Mujeres</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center">
                    <td>0-9</td>
                    <td>
                        {{ $age0male = $ageRangeArray[0]['male'] }}
                    </td>
                    <td>
                        {{ $age0female = $ageRangeArray[0]['female'] }}
                    </td>
                    <td>
                        {{ $age0total = $age0male + $age0female }}
                    </td>
                </tr>
                <tr class="text-center">
                    <td>10-19</td>
                    <td>
                        {{ $age1male = $ageRangeArray[1]['male'] }}
                    </td>
                    <td>
                        {{ $age1female = $ageRangeArray[1]['female'] }}
                    </td>
                    <td>
                        {{ $age1total = $age1male + $age1female }}
                    </td>
                </tr>
                <tr class="text-center">
                    <td>20-29</td>
                    <td>
                        {{ $age2male = $ageRangeArray[2]['male'] }}
                    </td>
                    <td>
                        {{ $age2female = $ageRangeArray[2]['female'] }}
                    </td>
                    <td>
                        {{ $age2total = $age2male + $age2female }}
                    </td>
                </tr>
                <tr class="text-center">
                    <td>30-39</td>
                    <td>
                        {{ $age3male = $ageRangeArray[3]['male'] }}
                    </td>
                    <td>
                        {{ $age3female = $ageRangeArray[3]['female'] }}
                    </td>
                    <td>
                        {{ $age3total = $age3male + $age3female }}
                    </td>
                </tr>
                <tr class="text-center">
                    <td>40-49</td>
                    <td>
                        {{ $age4male = $ageRangeArray[4]['male'] }}
                    </td>
                    <td>
                        {{ $age4female = $ageRangeArray[4]['female'] }}
                    </td>
                    <td>
                        {{ $age4total = $age4male + $age4female }}
                    </td>
                </tr>
                <tr class="text-center">
                    <td>50-59</td>
                    <td>
                        {{ $age5male = $ageRangeArray[5]['male'] }}
                    </td>
                    <td>
                        {{ $age5female = $ageRangeArray[5]['female'] }}
                    </td>
                    <td>
                        {{ $age5total = $age5male + $age5female }}
                    </td>
                </tr>
                <tr class="text-center">
                    <td>60-69</td>
                    <td>
                        {{ $age6male = $ageRangeArray[6]['male'] }}
                    </td>
                    <td>
                        {{ $age6female = $ageRangeArray[6]['female'] }}
                    </td>
                    <td>
                        {{ $age6total = $age6male + $age6female }}
                    </td>
                </tr>
                <tr class="text-center">
                    <td>70-79</td>
                    <td>
                        {{ $age7male = $ageRangeArray[7]['male'] }}
                    </td>
                    <td>
                        {{ $age7female = $ageRangeArray[7]['female'] }}
                    </td>
                    <td>
                        {{ $age7total = $age7male + $age7female }}
                    </td>
                </tr>
                <tr class="text-center">
                    <td>80-></td>
                    <td>
                        {{ $age8male = $ageRangeArray[8]['male'] }}
                    </td>
                    <td>
                        {{ $age8female = $ageRangeArray[8]['female'] }}
                    </td>
                    <td>
                        {{ $age8total = $age8male + $age8female }}
                    </td>
                </tr>
                <tr class="text-center">
                    <td>Sin Registro</td>
                    <td></td>
                    <td></td>
                    <td>
                        {{ $ageRangeArray[9]['null'] }}
                    </td>

                </tr>
            </tbody>
        </table>

        <div id="chart_ages" style="width: 400px; height: 400px"></div>
    </div>
</div>





@endsection

@section('custom_js_head')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart','line']});

    google.charts.setOnLoadCallback(drawChartAges);
    google.charts.setOnLoadCallback(drawChartEvolution);

    function drawChartAges() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Edad');
        data.addColumn('number', 'Hombres');
        data.addColumn('number', 'Mujeres');
        data.addRows([
            ['0-9',  {{ $age0male }} , {{ $age0female }}],
            ['10-19', {{ $age1male }} , {{ $age1female }}],
            ['20-29', {{ $age2male }} , {{ $age2female }}],
            ['30-39', {{ $age3male }} , {{ $age3female }}],
            ['40-49', {{ $age4male }} , {{ $age4female }}],
            ['50-59', {{ $age5male }} , {{ $age5female }}],
            ['60-69', {{ $age6male }} , {{ $age6female }}],
            ['70-79', {{ $age7male }} , {{ $age7female }}],
            ['80->',{{ $age8male }} , {{ $age8female }}],
        ]);

        var options = {
            title: 'Cantidad de casos positivos por edad',
            curveType: 'log',
            width: 380,
            height: 400,
            colors: ['#3366CC', '#CC338C'],
            legend: { position: 'bottom' },
            backgroundColor: '#f8fafc',
            chartArea: {'width': '85%', 'height': '80%'},
            hAxis: {
                textStyle : {
                    fontSize: 9 // or the number you want
                },
                textPosition: '50',
            },
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_ages'));

        chart.draw(data, options);
    }


    function drawChartEvolution() {
        var dataTable = new google.visualization.DataTable();
        dataTable.addColumn('number', 'Día');
        dataTable.addColumn('number', 'Positivos');
        // A column for custom tooltip content
        dataTable.addColumn({type: 'string', role: 'tooltip'});
        dataTable.addRows([
            @foreach($evolucion as $key => $total)
                [{{ $loop->iteration }},{{ $total }},'{{ $key }} ({{ $total }})'],
            @endforeach
        ]);

        var options = {
            title: "Comportamiento de casos positivos en el tiempo",
            curveType: 'function',
            width: 380,
            height: 400,
            legend: { position: "none" },
            backgroundColor: '#f8fafc',
            hAxis: {
                textStyle : {
                    format: 'short',
                    fontSize: 9 // or the number you want
                },
                gridlines:{count: {{ count($evolucion) }} },
                textPosition: '50',
            },
            chartArea: {'width': '80%', 'height': '80%'},

        };
        var chart = new google.visualization.LineChart(document.getElementById('evolution'));
        chart.draw(dataTable, options);
    }

</script>
@endsection
