@extends('layouts.app')

@section('title', 'Listado de Egresos')

@section('content')

<h3>Listado de Egresos</h3>

<form class="form-inline" method="get" action="{{ route('epp.index') }}">
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

<br />

<div class="table-responsive">
	<table class="table table-striped table-sm" id="myTable" border="1" >
		<thead>
			<tr>
				{{-- <th scope="col">id</th> --}}
				{{-- <th scope="col">Fecha</th> --}}
				<th scope="col">Destino</th>
				{{-- <th scope="col">Notas</th> --}}
				{{-- <th scope="col">Cant.</th> --}}
        <th scope="col">Producto</th>
        {{-- <th scope="col">Unidad</th> --}}
        {{-- <th scope="col">F.Venc.</th>
        <th scope="col">Lote</th> --}}
			</tr>
		</thead>
		<tbody>
			@foreach($dispatchs as $key => $dispatch)
        @foreach ($dispatch->dispatchItems as $key2 => $dispatchItem)
          <tr>
    				{{-- <td>{{ $dispatch->id }}</td> --}}
        		{{-- <td>{{ Carbon\Carbon::parse($dispatch->date)->format('d/m/Y')}}</td> --}}
        		<td ><b>{{ Carbon\Carbon::parse($dispatch->date)->format('d/m/Y')}}</b> <br /> {{ $dispatch->establishment }}</td>
        		{{-- <td>{{ $dispatch->notes }}</td> --}}
						{{-- <td >{{ $dispatchItem->amount }} {{ $dispatchItem->unity }}</td> --}}
            <td ><b>{{ $dispatchItem->amount }} {{ $dispatchItem->unity }}</b> - {{ $dispatchItem->product }}</td>
            {{-- <td>{{ $dispatchItem->unity }}</td> --}}
            {{-- <td>{{ $dispatchItem->due_date }}</td>
            <td>{{ $dispatchItem->batch }}</td> --}}
          </tr>
        @endforeach
			@endforeach
		</tbody>
	</table>
</div>

{{-- {{ $dispatchs->links() }} --}}

@endsection

@section('custom_js')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script type="text/javascript">

    //función que agrupa tabla
    $(function() {
      function groupTable($rows, startIndex, total){
      if (total === 0){return;}
      var i , currentIndex = startIndex, count=1, lst=[];
      var tds = $rows.find('td:eq('+ currentIndex +')');
      var ctrl = $(tds[0]);
      lst.push($rows[0]);
      for (i=1;i<=tds.length;i++){
        if (ctrl.text() ==  $(tds[i]).text()){
          count++;
          $(tds[i]).addClass('deleted');
          lst.push($rows[i]);
        }
        else{
          if (count>1){
            ctrl.attr('rowspan',count);
            //ctrl.classList.add("header");
            groupTable($(lst),startIndex+1,total-1)
          }
          count=1;
          lst = [];
          ctrl=$(tds[i]);
          lst.push($rows[i]);
        }
      }
      }
      groupTable($('#myTable tr:has(td)'),0,3);
      $('#myTable .deleted').remove();
    });

  </script>
@endsection
