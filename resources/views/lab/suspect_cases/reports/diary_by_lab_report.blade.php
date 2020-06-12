@extends('layouts.app')

@section('title', 'Reporte COVID-19')

@section('content')


<div class="row">

  <div class="col-12">
    <h5 class="mb-3">Exámenes realizados por laboratorios</h5>
    <div class="table-responsive-sm">
      <table class="table table-sm table-bordered text-center table-striped small">
        <thead>
          <tr class="text-center">
            <th>Fecha</th>
            @foreach($cases_by_days as $key => $cases_by_day)
              @foreach($cases_by_day['laboratories'] as $key_labs => $lab)
                <th>{{ $key_labs }}</th>
              @endforeach
              @break
            @endforeach
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
            @foreach($cases_by_days as $key => $cases_by_day)
              <tr class="text-center">
              <td>{{ $key }}</td>
              @foreach($cases_by_day['laboratories'] as $key_labs => $lab)
                <td>{{ $lab }}</td>
              @endforeach
              <td>{{ $cases_by_day['cases'] }}</td>
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
