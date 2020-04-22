@extends('layouts.app')

@section('title', 'Reporte COVID-19')

@section('content')


<div class="row">
  <div class="col-12 col-md-4">

    <h3 class="mb-3">Cantidad toma de muestras diarias</h3>
    <table class="table table-sm table-bordered">
        <thead>
            <tr class="text-center">
                <th>Día</th>
                <th>Cant.Exámenes</th>
            </tr>
        </thead>
        <tbody>
          @foreach($total_muestras_diarias as $muestra_diaria)
            <tr>
                <td>{{ Carbon\Carbon::parse($muestra_diaria->sample_at)->format('Y-m-d') }}</td>
                <td>{{ $muestra_diaria->total }}</td>
            </tr>
          @endforeach
        </tbody>
    </table>
  </div>

  <div class="col-12 col-md-6">
    <h3 class="mb-3">Cantidad exámenes realizados por laboratorios</h3>
    <table class="table table-sm table-bordered">
        <thead>
            <tr class="text-center">
                <th>Día</th>
                @foreach($total_muestras_x_lab_columnas as $key => $muestra_x_lab_columna)
                  <th>{{$key}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
          @foreach($total_muestras_x_lab_filas as $key => $muestra_x_lab_filas)
            <tr>
              <td nowrap>{{Carbon\Carbon::parse($key)->format('Y-m-d')}}</td>
              @foreach($total_muestras_x_lab_columnas as $key2 => $muestra_x_lab_columna)
                  <td>
                    @foreach ($muestra_x_lab_filas as $key3 => $data)
                      @if($key2 == $key3)
                        {{$data['cantidad']}}
                      @endif
                    @endforeach
                  </td>
              @endforeach
            </tr>
          @endforeach
        </tbody>
    </table>
  </div>
</div>

{{-- <br /><hr /> --}}

@endsection

@section('custom_js_head')
<script type="text/javascript">

</script>
@endsection
