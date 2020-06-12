@extends('layouts.app')

@section('title', 'Listado de encuestas')

@section('content')
<h3 class="mb-3">Listado de encuestas</h3>



@foreach($encuestas as $enc)

    <li>{{ $enc->respuesta }}</li>

@endforeach



@endsection

@section('custom_js')

@endsection
