@extends('layouts.app')

@section('title', '')

@section('content')
<h3 class="mb-3">Resultado de examenes de</h3>
{{ Auth::user()->name }}

@endsection

@section('custom_js')

@endsection
