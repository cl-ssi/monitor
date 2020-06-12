@extends('layouts.app')

@section('title', 'Reporte Casos Positivos')

@section('content')
<h3 class="mb-3">Reporte Casos Positivos</h3>

<div class="row">
    <div class="col-12 col-md-4 mb-3">

        <table class="table table-sm table-bordered">
            <thead>
                <tr class="table-active">
                    <th>Casos de la Región</th>
                    <th>Hom</th>
                    <th>Muj</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Positivos</td>
                    <th class="text-center">
                        {{ $total_male = $patients->where('gender','male')->count() }}
                    </th>
                    <th class="text-center">
                        {{ $total_female = $patients->where('gender','female')->count() }}
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
                <tr>
                    <th class="table-active">Hospitalizados</th>
                    <th class="table-active text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Hospitalizados Básicos</th>
                    <td class="text-right">{{ $hbasico = $patients->where('status', 'Hospitalizado Básico')->count() }}</td>
                </tr>
                <tr>
                    <th>Hospitalizados Medios</th>
                    <td class="text-right">{{ $hmedio = $patients->where('status', 'Hospitalizado Medio')->count() }}</td>
                </tr>
                <tr>
                    <th>Hospitalizados UTI</th>
                    <td class="text-right">{{ $huti = $patients->where('status', 'Hospitalizado UTI')->count() }}</td>
                </tr>
                <tr>
                    <th>Hospitalizados UCI</th>
                    <td class="text-right">{{ $huci = $patients->where('status', 'Hospitalizado UCI')->count() }}</td>
                </tr>
                <tr>
                    <th>Hospitalizado UCI (Ventilador)</th>
                    <td class="text-right">{{ $huciv = $patients->where('status', 'Hospitalizado UCI (Ventilador)')->count() }}</td>
                </tr>
                @foreach($patients->where('status', 'Hospitalizado UCI (Ventilador)') as $patient)
                <tr>
                    <td colspan="2"> - {{ $patient->genderEsp }} - {{ $patient->age }} - {{ ($patient->demographic) ? $patient->demographic->commune:'' }}</td>
                </tr>
                @endforeach
                <tr>
                    <th>Total</th>
                    <th class="text-right">{{ $hbasico + $hmedio + $huti + $huci + $huciv }}</th>
                </tr>
            </tbody>
        </table> -->


        <table class="table table-sm table-bordered">
            <thead>
                <tr class="table-active">
                    <th colspan="5">Cantidad de Ventiladores <small>({{ $ventilator->updated_at }})</small> </th>
                    <th class="text-center">{{ $ventilator->total }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="color: red;">Covid-19+</td>
                    <th class="text-center">{{ $huciv }}</th>
                    <td>No Covid-19</td>
                    <th class="text-center">{{ $ventilator->no_covid }}</th>
                    <td>Disponibles</td>
                    <th class="text-center">
                        {{ $ventilator->total - $huciv - $ventilator->no_covid }}
                    </th>
                </tr>
            </tbody>
        </table>

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
                        {{ $fallecido_male = $patients->where('gender','male')->where('status','Fallecido')->count() }}
                    </td>
                    <td class="text-right">
                        {{ $fallecido_female = $patients->where('gender','female')->where('status','Fallecido')->count() }}
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
                        {{ $commune->count = $patients->where('demographic.commune_id',$commune->id)->count() }}
                    </td>
                </tr>
                @endforeach

                <tr>
                    <td>Sin registro</td>
                    <td class="text-center">
                        {{ $sin_registro = $patients->whereIn('demographic.commune',['sin-comuna',null])->count() }}
                    </td>
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
                        {{ $age0male = $patients->whereBetween('age',[0,9])->where('gender','male')->count() }}
                    </td>
                    <td>
                        {{ $age0female = $patients->whereBetween('age',[0,9])->where('gender','female')->count() }}
                    </td>
                    <td>
                        {{ $age0total = $age0male + $age0female }}
                    </td>
                </tr>
                <tr class="text-center">
                    <td>10-19</td>
                    <td>
                        {{ $age1male = $patients->whereBetween('age',[10,19])->where('gender','male')->count() }}
                    </td>
                    <td>
                        {{ $age1female = $patients->whereBetween('age',[10,19])->where('gender','female')->count() }}
                    </td>
                    <td>
                        {{ $age1total = $age1male + $age1female }}
                    </td>
                </tr>
                <tr class="text-center">
                    <td>20-29</td>
                    <td>
                        {{ $age2male = $patients->whereBetween('age',[20,29])->where('gender','male')->count() }}
                    </td>
                    <td>
                        {{ $age2female = $patients->whereBetween('age',[20,29])->where('gender','female')->count() }}
                    </td>
                    <td>
                        {{ $age2total = $age2male + $age2female }}
                    </td>
                </tr>
                <tr class="text-center">
                    <td>30-39</td>
                    <td>
                        {{ $age3male = $patients->whereBetween('age',[30,39])->where('gender','male')->count() }}
                    </td>
                    <td>
                        {{ $age3female = $patients->whereBetween('age',[30,39])->where('gender','female')->count() }}
                    </td>
                    <td>
                        {{ $age3total = $age3male + $age3female }}
                    </td>
                </tr>
                <tr class="text-center">
                    <td>40-49</td>
                    <td>
                        {{ $age4male = $patients->whereBetween('age',[40,49])->where('gender','male')->count() }}
                    </td>
                    <td>
                        {{ $age4female = $patients->whereBetween('age',[40,49])->where('gender','female')->count() }}
                    </td>
                    <td>
                        {{ $age4total = $age4male + $age4female }}
                    </td>
                </tr>
                <tr class="text-center">
                    <td>50-59</td>
                    <td>
                        {{ $age5male = $patients->whereBetween('age',[50,59])->where('gender','male')->count() }}
                    </td>
                    <td>
                        {{ $age5female = $patients->whereBetween('age',[50,59])->where('gender','female')->count() }}
                    </td>
                    <td>
                        {{ $age5total = $age5male + $age5female }}
                    </td>
                </tr>
                <tr class="text-center">
                    <td>60-69</td>
                    <td>
                        {{ $age6male = $patients->whereBetween('age',[60,69])->where('gender','male')->count() }}
                    </td>
                    <td>
                        {{ $age6female = $patients->whereBetween('age',[60,69])->where('gender','female')->count() }}
                    </td>
                    <td>
                        {{ $age6total = $age6male + $age6female }}
                    </td>
                </tr>
                <tr class="text-center">
                    <td>70-79</td>
                    <td>
                        {{ $age7male = $patients->whereBetween('age',[70,79])->where('gender','male')->count() }}
                    </td>
                    <td>
                        {{ $age7female = $patients->whereBetween('age',[70,79])->where('gender','female')->count() }}
                    </td>
                    <td>
                        {{ $age7total = $age7male + $age7female }}
                    </td>
                </tr>
                <tr class="text-center">
                    <td>80-></td>
                    <td>
                        {{ $age8male = $patients->whereBetween('age',[80,999])->where('gender','male')->count() }}
                    </td>
                    <td>
                        {{ $age8female = $patients->whereBetween('age',[80,999])->where('gender','female')->count() }}
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
                        {{ $patients->whereNull('age')->count() }}
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
            @foreach($evolucion['Region'] as $key => $total)
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
                    fontSize: 9 // or the number you want
                },
                gridlines:{count: {{ count($evolucion['Region']) }} },
                textPosition: '50',
            },
            chartArea: {'width': '80%', 'height': '80%'},

        };
        var chart = new google.visualization.LineChart(document.getElementById('evolution'));
        chart.draw(dataTable, options);
    }

</script>
@endsection
