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
            @foreach($total_muestras_labs as $total_muestras_lab)
              @foreach($total_muestras_lab as $nombre_lab => $lab)
                  <th>@if($nombre_lab == 'date')
                        Día
                      @elseif($nombre_lab == 'total')
                        Total
                      @else
                        {{ $nombre_lab }}
                      @endif
                  </th>
              @endforeach
              @break
            @endforeach
          </tr>
        </thead>
        <tbody>
          @foreach($total_muestras_labs as $total_muestras_lab)
            <tr>
            @foreach($total_muestras_lab as $nombre_lab => $lab)
                <td>{{ $lab }}</td>
            @endforeach
            </tr>
          @endforeach
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
