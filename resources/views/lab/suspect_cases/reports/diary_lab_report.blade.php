@extends('layouts.app')

@section('title', 'Reporte COVID-19')

@section('content')


<div class="row">
  <div class="col">

    <h5 class="mb-3">Toma de muestras diarias</h5>

    <div class="table-responsive-sm">
      <table class="table table-sm table-bordered text-center table-striped small">
        <thead>
          <tr class="text-center">
            <th rowspan="2">Día</th>
            <th rowspan="2">N° Muestras</th>
            <th colspan="5">Resultados</th>
            <th rowspan="2">Procesados</th>
          </tr>
          <tr class="text-center">
            <th>Positivos</th>
            <th>Negativos</th>
            <th>Muestra no apta</th>
            <th>Indeterminado</th>
            <th>Pendiente</th>
          </tr>
        </thead>
        <tbody>
          @foreach($cases_by_days as $key => $cases)
          <tr>
            <td>{{ $key }}</td>
            <td>{{ $cases['cases'] }}</td>
            <td>{{ $cases['positive'] }}</td>
            <td>{{ $cases['negative'] }}</td>
            <td>{{ $cases['rejected'] }}</td>
            <td>{{ $cases['undetermined'] }}</td>
            <td>{{ $cases['pending'] }}</td>
            <td>{{ $cases['procesing'] }}</td>
          </tr>
          @endforeach
          <tr>
            <td>Total</td>
            @foreach($total_cases_by_days as $key => $cases)
              <td>{{ $cases }}</td>
            @endforeach
          </tr>
        </tbody>
      </table>
    </div>
  </div>

</div>

{{-- <br /><hr /> --}}

@endsection

@section('custom_js_head')
<script type="text/javascript">

</script>
@endsection
