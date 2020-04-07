@extends('layouts.app')

@section('title', 'Reporte cantidad de casos sospechosos - positivos')

@section('content')

<h3 class="mb-3">Reporte de casos</h3>

<form class="form-inline" method="post" action="{{ route('lab.suspect_cases.case_chart') }}">
	@csrf

	<div class="form-group ml-3">
		<label for="for_from">Desde</label>
		<input type="date" class="form-control mx-sm-3" id="for_from" name="from"
			value="{{ Carbon\Carbon::parse($from)->format('Y-m-d') }}">
	</div>

	<div class="form-group">
		<label for="for_to">Hasta</label>
		<input type="date" class="form-control mx-sm-3" id="for_to" name="to"
			value="{{ Carbon\Carbon::parse($to)->format('Y-m-d') }}">
	</div>

	<div class="form-group">
		<button type="submit" name="btn_buscar" class="btn btn-primary">Buscar</button>
	</div>
</form>

<div id="chart_div" style="height: 500px;"></div>

@endsection



@section('custom_js')

@endsection

@section('custom_js_head')

  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">

      // google.charts.load('current', {'packages':['bar']});
      // google.charts.load("current", {packages:['corechart']});
      // google.charts.setOnLoadCallback(drawChart);
      //
      // function drawChart() {
      //   var data = google.visualization.arrayToDataTable([
      //     ['Fecha', 'Pendientes', 'Positivos'],
      //
      //     @foreach($data as $suspectCase)
      //       [new Date({{$suspectCase['year']}}, {{$suspectCase['month']}}, {{$suspectCase['day']}}), {{$suspectCase['pendientes']}}, {{$suspectCase['positivos']}}],
      //     @endforeach
      //
      //   ]);
      //
      //   var view = new google.visualization.DataView(data);
      //   view.setColumns([0, 1,
      //                    { calc: "stringify",
      //                      sourceColumn: 1,
      //                      type: "string",
      //                      role: "annotation" },
      //                    2]);
      //
      //
      //   var options = {
      //     chart: {
      //       title: 'Seguimiento de casos',
      //       subtitle: 'Pendientes vs Positivos'
      //     }
      //   };
      //
      //   var chart = new google.charts.Bar(document.getElementById('chart_div'));
      //   chart.draw(view, options);
      //
      //   // chart.draw(data, google.charts.Bar.convertOptions(options));
      // }

    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
      ['Fecha', 'Pendientes', 'Positivos'],

      @foreach($data as $key => $suspectCase)
        ['{{$key}}', {{$suspectCase['pendientes']}}, {{$suspectCase['positivos']}}],
      @endforeach
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2,
                       { calc: "stringify",
                         sourceColumn: 2,
                         type: "string",
                         role: "annotation" }]);

      var options = {
        title: "Seguimiento de casos - Pendientes vs Positivos",
        subtitle: 'Pendientes vs Positivos',
        // width: 600,
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("chart_div"));
      chart.draw(view, options);
    }

  </script>


@endsection
