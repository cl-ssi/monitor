@extends('layouts.app')

@section('title', 'Buscador por ID')

@section('content')
<div class="row">
    <div class="col-12 col-sm-12 col-md-12" align="right">
        @include('lab.suspect_cases.partials.search_id')
    </div>
</div>
<!---END row--->
@endsection