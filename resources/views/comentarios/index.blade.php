@extends('layouts.app')

@section('title', 'Listado de comentarios')

@section('content')
<h3 class="mb-3">Listado de comentarios</h3>


<ul>

    @foreach($comentarios as $com)

        <li> {{ $com->fecha }} - {{ $com->texto }}</li>

    @endforeach

</ul>



@endsection

@section('custom_js')

@endsection
