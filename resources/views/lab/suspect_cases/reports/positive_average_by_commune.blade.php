@extends('layouts.app')

@section('title', 'Reporte COVID-19')

@section('content')


<div class="row">
  <div class="col">

    <h5 class="mb-3">Tasa de positividad de últimos 30 días por comuna</h5>

    <div class="table-responsive-sm">
      <table class="table table-sm table-bordered text-center table-striped small">
        <thead>
          <tr class="text-center">
            <th rowspan="2">Día</th>
            @foreach($cases_by_days as $key => $cases)
                @foreach($cases as $key2 => $case)
                    <th>{{$key2}}</th>
                @endforeach
                @break
            @endforeach
          </tr>
        </thead>
        <tbody>
            @foreach($cases_by_days as $key => $cases)
                <tr>
                    <td>{{$key}}</td>
                    @foreach($cases as $key2 => $case)
                        @if($case['total'] != 0)
                            <td>{{$case['positivos']}} de {{$case['total']}}<p style="color:red"> ({{round($case['positivos']*100/$case['total'],2)}}%)</p></td>
                        @else
                            <td>0 de 0 <br /><p style="color:red">0%</p></td>
                        @endif
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
