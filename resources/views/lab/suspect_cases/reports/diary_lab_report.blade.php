@extends('layouts.app')

@section('title', 'Reporte COVID-19')

@section('content')


<div class="row">
  <div class="col-3">

    <h5 class="mb-3">Toma de muestras diarias</h5>

    <div class="table-responsive-sm">
      <table class="table table-sm table-bordered text-center table-striped small">
        <thead>
          <tr class="text-center">
            <th>Día</th>
            <th>N° Muestras</th>
          </tr>
        </thead>
        <tbody>
          @foreach($resumeSuspectCases as $resumeSuspectCase)
          <tr>
            <td>{{ Carbon\Carbon::parse($resumeSuspectCase->date)->format('Y-m-d') }}</td>
            <td>{{ $resumeSuspectCase->count }}</td>
          </tr>
          @endforeach
          <tr>
            <td>Total</td>
            <td>{{ $resumeSuspectCases->sum('count') }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="col-9">
    <h5 class="mb-3">Exámenes realizados por laboratorios</h5>
    <div class="table-responsive-sm">
      <table class="table table-sm table-bordered text-center table-striped small">
        <thead>
          <tr class="text-center">
            <th>Día</th>
            @foreach($total_muestras_x_lab_columnas as $key => $muestra_x_lab_columna)
            <th>{{$key}}</th>
            @endforeach
            <th><b>Total</b></th>
          </tr>
        </thead>
        <tbody>
          @foreach($total_muestras_x_lab_filas as $key => $muestra_x_lab_filas)
          @php $cont = 0; @endphp
          <tr>
            <td nowrap>{{Carbon\Carbon::parse($key)->format('Y-m-d')}}</td>
            @foreach($total_muestras_x_lab_columnas as $key2 => $muestra_x_lab_columna)
            <td>
            @foreach ($muestra_x_lab_filas as $key3 => $data)
              @if($key2 == $key3)
                {{$data['cantidad']}}
                @php $cont += $data['cantidad']; @endphp
              @endif
            @endforeach
            </td>
          @endforeach
            <td><b>{{$cont}}</b></td>
          </tr>
          @endforeach
          <tr class="text-center">
            <td><b>Total</b></td>
            @foreach($total_muestras_x_lab_columnas as $key => $muestra_x_lab_columna)
            <td><b>{{$muestra_x_lab_columna}}</b></td>
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
