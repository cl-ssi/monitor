@extends('layouts.app')

@section('title', 'Reporte COVID-19')

@section('content')
<h3 class="mb-3">Reporte histórico COVID-19</h3>

<form class="form-inline" method="get" action="{{ route('lab.suspect_cases.report.historical_report') }}">
	@csrf
	<div class="form-group ml-3">
		<label for="for_from">Fecha</label>
		<input type="date" class="form-control mx-sm-3" id="for_date" name="date"
			value="{{ Carbon\Carbon::parse($date)->format('Y-m-d') }}">
	</div>

	<div class="form-group">
		<button type="submit" name="btn_buscar" class="btn btn-primary">Buscar</button>
	</div>
</form>

<br /><hr />

@php
	echo htmlspecialchars_decode($main);
@endphp

@endsection

@section('custom_js_head')

	@php
		echo htmlspecialchars_decode($head);
	@endphp

@endsection
