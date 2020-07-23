@extends('layouts.app')

@section('title', 'Listado de fallecidos')

@section('content')
<h3 class="mb-3">Listado de fallecidos</h3>

<table class="table table-sm table-bordered small">
    <thead>
        <tr>
            <th>Ct</th>
            <th>Nombre</th>
            <th>Comuna</th>
            <th>Fecha de defunción</th>
            <th>Observación</th>
        </tr>
    </thead>
    <tbody>
        @foreach($patients->reverse() as $key => $patient)
        <tr>
            <td>{{ ++$key }}</td>
            <td nowrap>
                <a href="{{ route('patients.edit',$patient)}}">
                {{ $patient->fullName }}
                </a>
            </td>
            <td>{{ ($patient->demographic AND $patient->demographic->commune) ? $patient->demographic->commune->name : ''}}</td>
            <td nowrap>{{ ($patient->deceased_at) ? $patient->deceased_at->format('Y-m-d') : '' }}</td>
            <td>{{ $patient->suspectCases->first()->observation }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div id="chart_ages" style="width: 400px; height: 400px; margin:auto"></div>

    @php

        $age0male = $patients->whereBetween('age',[0,9])->where('gender','male')->count();
        $age0female = $patients->whereBetween('age',[0,9])->where('gender','female')->count();
        $age0total = $age0male + $age0female;
        $age1male = $patients->whereBetween('age',[10,19])->where('gender','male')->count();
        $age1female = $patients->whereBetween('age',[10,19])->where('gender','female')->count();
        $age1total = $age1male + $age1female;
        $age2male = $patients->whereBetween('age',[20,29])->where('gender','male')->count();
        $age2female = $patients->whereBetween('age',[20,29])->where('gender','female')->count();
        $age2total = $age2male + $age2female;
        $age3male = $patients->whereBetween('age',[30,39])->where('gender','male')->count();
        $age3female = $patients->whereBetween('age',[30,39])->where('gender','female')->count();
        $age3total = $age3male + $age3female;
        $age4male = $patients->whereBetween('age',[40,49])->where('gender','male')->count();
        $age4female = $patients->whereBetween('age',[40,49])->where('gender','female')->count();
        $age4total = $age4male + $age4female;
        $age5male = $patients->whereBetween('age',[50,59])->where('gender','male')->count();
        $age5female = $patients->whereBetween('age',[50,59])->where('gender','female')->count();
        $age5total = $age5male + $age5female;
        $age6male = $patients->whereBetween('age',[60,69])->where('gender','male')->count();
        $age6female = $patients->whereBetween('age',[60,69])->where('gender','female')->count();
        $age6total = $age6male + $age6female;
        $age7male = $patients->whereBetween('age',[70,79])->where('gender','male')->count();
        $age7female = $patients->whereBetween('age',[70,79])->where('gender','female')->count();
        $age7total = $age7male + $age7female;
        $age8male = $patients->whereBetween('age',[80,999])->where('gender','male')->count();
        $age8female = $patients->whereBetween('age',[80,999])->where('gender','female')->count();
        $age8total = $age8male + $age8female;

    @endphp

@endsection

@section('custom_js_head')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart','line']});

        google.charts.setOnLoadCallback(drawChartAges);

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
                title: 'Cantidad de fallecidos por edad',
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

    </script>
@endsection
