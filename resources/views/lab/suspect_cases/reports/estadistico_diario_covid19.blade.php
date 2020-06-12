@extends('layouts.app')

@section('title', 'Reporte COVID-19')

@section('content')

<h3 class="mb-3">Reporte estadístico diario COVID19</h3>
<h5 class="mb-3">Información desde {{$yesterday}} al {{$now}}</h3>

</main><main class="py-4">

<table class="table table-sm table-bordered table-responsive small" >
    <thead>
        <tr class="text-center">
            <th>Nombre de laboratorio</th>
            <th>Ciudad</th>
            <th>Stock muestras en espera</th>
            <th># de muestras recibidas </th>
            <th># muestras procesadas </th>
            <th>Stock Final muestras en espera</th>
            <th>Capacidad máxima de procesamiento diario</th>
            <th># muestras positivas </th>
            <th># muestras procesados acumulados</th>
            <th># muestras positivas acumulados</th>
            <th>Alerta cuello de botella</th>
        </tr>
    </thead>
    <tbody>
      @foreach($array as $key => $celda)
        <tr>
            <td nowrap>{{$key}}</td>
            <td nowrap>{{$celda['commune']}}</td>
            <td nowrap>{{$celda['muestras_en_espera']}}</td>
            <td nowrap>{{$celda['muestras_recibidas']}}</td>
            <td nowrap>{{$celda['muestras_procesadas']}}</td>
            <td nowrap></td>
            <td nowrap></td>
            <td nowrap>{{$celda['muestras_positivas']}}</td>
            <td nowrap>{{$celda['muestras_procesadas_acumulados']}}</td>
            <td nowrap>{{$celda['muestras_procesadas_positivo']}}</td>
            <td nowrap></td>

            {{-- $array[$case->laboratory->name]['muestras_en_espera'] = 0;
            $array[$case->laboratory->name]['muestras_recibidas'] = 0;
            $array[$case->laboratory->name]['muestras_procesadas'] = 0;
            $array[$case->laboratory->name]['muestras_positivas'] = 0;
            $array[$case->laboratory->name]['muestras_procesadas_acumulados'] = 0;
            $array[$case->laboratory->name]['muestras_procesadas_positivo'] = 0; --}}
        </tr>
      @endforeach
    </tbody>
</table>

@endsection

@section('custom_js_head')
<script type="text/javascript">

</script>
@endsection
