@extends('layouts.app')

@section('title', 'Contacto de Alto Riesgo a Indice')

@section('content')

<table class="table">
<thead class="thead-dark">
<tr>        
            <th scope="col">Usuario Creador</th>   
            <th scope="col">Index Antiguo</th>
            <th scope="col">Index Nuevo</th>
</tr>
</thead>
@foreach($audits as $audit)
@foreach($audit->old_values as $attributeold => $valueold)


@foreach($audit->new_values as $attributenew => $valuenew)
@if($attributeold == 'index' and $valueold == 0)
@if($attributenew == 'index' and $valuenew == 1)
<tr>
<td>{{ $audit->user->name }}</td>
<td><b>{{ $attributeold }}</b> {{ $valueold }}</td>
<td><b>{{ $attributenew }}</b> {{ $valuenew }}</td>
</tr>
@endif
@endif
@endforeach
@endforeach
@endforeach
<table>






@endsection

@section('custom_js')

@endsection